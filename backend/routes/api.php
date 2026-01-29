<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LeadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rota pública para criar lead a partir do formulário do site
Route::post('/leads', [LeadController::class, 'store']);

// Rotas protegidas (requer autenticação - implementar futuramente)
Route::middleware(['api'])->group(function () {
    // CRUD de Leads
    Route::get('/leads', [LeadController::class, 'index']);
    Route::get('/leads/{id}', [LeadController::class, 'show']);
    Route::put('/leads/{id}', [LeadController::class, 'update']);
    Route::post('/leads/{id}/interacoes', [LeadController::class, 'addInteracao']);
    
    // Estatísticas
    Route::get('/dashboard/stats', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'total_leads' => \App\Models\Lead::count(),
                'leads_novos' => \App\Models\Lead::where('status', 'novo')->count(),
                'leads_convertidos' => \App\Models\Lead::where('status', 'convertido')->count(),
                'leads_hoje' => \App\Models\Lead::whereDate('created_at', today())->count(),
                'leads_mes' => \App\Models\Lead::whereMonth('created_at', now()->month)->count(),
            ]
        ]);
    });
});

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'Síndico FK CRM API',
        'version' => '1.0.0',
        'timestamp' => now()->toIso8601String(),
    ]);
});
