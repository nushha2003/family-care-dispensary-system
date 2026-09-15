<?php

if (!function_exists('generatePatientNumber')) {
    function generatePatientNumber(): string
    {
        return 'PAT-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount): string
    {
        return '₦' . number_format($amount, 2);
    }
}

if (!function_exists('getGreeting')) {
    function getGreeting(): string
    {
        $hour = now()->hour;
        if ($hour < 12) return 'Good Morning';
        if ($hour < 17) return 'Good Afternoon';
        return 'Good Evening';
    }
}