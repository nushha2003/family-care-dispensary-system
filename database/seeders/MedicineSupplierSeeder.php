<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Medicine;

class MedicineSupplierSeeder extends Seeder
{
    public function run(): void
    {
        // ========== CREATE SUPPLIERS ==========
        $supplier1 = Supplier::create([
            'name' => 'MediSource Pharma',
            'phone' => '+1 234 567 8901',
            'email' => 'info@medisource.com',
            'address' => '123 Pharma Street, Medical City, MC 12345',
        ]);

        $supplier2 = Supplier::create([
            'name' => 'HealthPlus Distributors',
            'phone' => '+1 345 678 9012',
            'email' => 'sales@healthplus.com',
            'address' => '456 Wellness Avenue, Health Town, HT 67890',
        ]);

        $supplier3 = Supplier::create([
            'name' => 'Global Med Supplies',
            'phone' => '+1 456 789 0123',
            'email' => 'info@globalmed.com',
            'address' => '789 International Blvd, Global City, GC 11223',
        ]);

        // ========== CREATE MEDICINES ==========
        $medicines = [
            // [name, generic_name, category, unit, purchase_price, selling_price, current_stock, reorder_level, expiry_date, batch_no, supplier_id]
            [
                'Paracetamol 500mg',
                'Acetaminophen',
                'Analgesic',
                'tablet',
                5.00,
                10.00,
                500,
                50,
                '2026-12-31',
                'BATCH-001',
                $supplier1->id
            ],
            [
                'Amoxicillin 500mg',
                'Amoxicillin',
                'Antibiotic',
                'capsule',
                8.00,
                15.00,
                300,
                30,
                '2026-10-15',
                'BATCH-002',
                $supplier1->id
            ],
            [
                'Cetirizine 10mg',
                'Cetirizine HCl',
                'Antihistamine',
                'tablet',
                2.00,
                5.00,
                200,
                20,
                '2027-01-01',
                'BATCH-003',
                $supplier2->id
            ],
            [
                'Ibuprofen 400mg',
                'Ibuprofen',
                'NSAID',
                'tablet',
                4.00,
                8.00,
                150,
                25,
                '2026-11-30',
                'BATCH-004',
                $supplier1->id
            ],
            [
                'Omeprazole 20mg',
                'Omeprazole',
                'Proton Pump Inhibitor',
                'capsule',
                6.00,
                12.00,
                120,
                15,
                '2027-03-15',
                'BATCH-005',
                $supplier2->id
            ],
            [
                'Salbutamol Inhaler',
                'Salbutamol',
                'Bronchodilator',
                'inhaler',
                15.00,
                25.00,
                80,
                10,
                '2026-09-30',
                'BATCH-006',
                $supplier3->id
            ],
            [
                'Metformin 500mg',
                'Metformin HCl',
                'Antidiabetic',
                'tablet',
                7.00,
                14.00,
                250,
                30,
                '2027-02-28',
                'BATCH-007',
                $supplier2->id
            ],
            [
                'Amlodipine 5mg',
                'Amlodipine Besylate',
                'Antihypertensive',
                'tablet',
                9.00,
                18.00,
                180,
                20,
                '2027-04-15',
                'BATCH-008',
                $supplier1->id
            ],
            [
                'Ciprofloxacin 500mg',
                'Ciprofloxacin',
                'Antibiotic',
                'tablet',
                10.00,
                20.00,
                100,
                15,
                '2026-08-20',
                'BATCH-009',
                $supplier3->id
            ],
            [
                'Vitamin C 1000mg',
                'Ascorbic Acid',
                'Vitamin',
                'tablet',
                3.00,
                6.00,
                400,
                40,
                '2027-06-30',
                'BATCH-010',
                $supplier2->id
            ],
        ];

        foreach ($medicines as $med) {
            Medicine::create([
                'name' => $med[0],
                'generic_name' => $med[1],
                'category' => $med[2],
                'unit' => $med[3],
                'purchase_price' => $med[4],
                'selling_price' => $med[5],
                'current_stock' => $med[6],
                'reorder_level' => $med[7],
                'expiry_date' => $med[8],
                'batch_no' => $med[9],
                'supplier_id' => $med[10],
            ]);
        }

        $this->command->info('✅ Suppliers and medicines seeded successfully!');
    }
}