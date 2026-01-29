<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Síndico FK CRM API',
        'version' => '1.0.0',
        'status' => 'active',
        'endpoints' => [
            'health' => '/api/health',
            'leads' => '/api/leads',
            'dashboard' => '/api/dashboard/stats'
        ]
    ]);
});
