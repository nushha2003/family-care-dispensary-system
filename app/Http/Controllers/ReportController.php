<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Show daily revenue report.
     */
    public function dailyRevenue(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        // Get prescriptions dispensed on this date
        $prescriptions = Prescription::with(['items.medicine', 'patient', 'doctor'])
            ->whereDate('updated_at', $date)
            ->where('is_dispensed', true)
            ->get();

        // Calculate totals
        $totalRevenue = 0;
        $totalConsultation = 0;
        $totalMedicineSales = 0;
        $medicineSales = [];

        foreach ($prescriptions as $prescription) {
            $consultationFee = 0; // You can set a fixed fee or dynamic if you add it to DB
            $medicineTotal = 0;

            foreach ($prescription->items as $item) {
                $subtotal = $item->quantity * $item->medicine->selling_price;
                $medicineTotal += $subtotal;
               
                // Track per medicine
                if (!isset($medicineSales[$item->medicine->name])) {
                    $medicineSales[$item->medicine->name] = [
                        'name' => $item->medicine->name,
                        'quantity' => 0,
                        'revenue' => 0,
                    ];
                }
                $medicineSales[$item->medicine->name]['quantity'] += $item->quantity;
                $medicineSales[$item->medicine->name]['revenue'] += $subtotal;
            }

            $totalConsultation += $consultationFee;
            $totalMedicineSales += $medicineTotal;
            $totalRevenue += $consultationFee + $medicineTotal;
        }

        // Sort medicine sales by revenue (descending)
        usort($medicineSales, function ($a, $b) {
            return $b['revenue'] <=> $a['revenue'];
        });

        $stats = [
            'total_prescriptions' => $prescriptions->count(),
            'total_revenue' => $totalRevenue,
            'total_consultation' => $totalConsultation,
            'total_medicine_sales' => $totalMedicineSales,
            'average_per_prescription' => $prescriptions->count() > 0
                ? round($totalRevenue / $prescriptions->count(), 2)
                : 0,
        ];

        return view('reports.daily', compact('date', 'prescriptions', 'stats', 'medicineSales'));
    }

    /**
     * Generate weekly summary report.
     */
    public function weeklyRevenue(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::now()->startOfWeek();
        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : Carbon::now()->endOfWeek();

        $prescriptions = Prescription::with(['items.medicine'])
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->where('is_dispensed', true)
            ->get();

        $totalRevenue = 0;
        $dailyData = [];

        foreach ($prescriptions as $prescription) {
            $dailyKey = $prescription->updated_at->format('Y-m-d');
            if (!isset($dailyData[$dailyKey])) {
                $dailyData[$dailyKey] = [
                    'date' => $dailyKey,
                    'count' => 0,
                    'revenue' => 0,
                ];
            }
           
            $dailyData[$dailyKey]['count']++;
           
            $prescriptionTotal = 0;
            foreach ($prescription->items as $item) {
                $prescriptionTotal += $item->quantity * $item->medicine->selling_price;
            }
            $dailyData[$dailyKey]['revenue'] += $prescriptionTotal;
            $totalRevenue += $prescriptionTotal;
        }

        return view('reports.weekly', compact('startDate', 'endDate', 'dailyData', 'totalRevenue'));
    }
}