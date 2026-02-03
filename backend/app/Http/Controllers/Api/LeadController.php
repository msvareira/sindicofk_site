<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Interacao;
use App\Mail\NewLeadNotification;
use App\Mail\LeadConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    /**
     * Criar novo lead a partir do formulário do site
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:20',
            'condominio' => 'nullable|string|max:255',
            'tipo_contato' => 'nullable|in:orcamento,duvida,informacao,reclamacao,outro',
            'mensagem' => 'required|string',
        ], [
            'nome.required' => 'O nome é obrigatório',
            'email.required' => 'O email é obrigatório',
            'email.email' => 'Formato de email inválido',
            'telefone.required' => 'O telefone é obrigatório',
            'mensagem.required' => 'A mensagem é obrigatória',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Criar lead
            $lead = Lead::create([
                'nome' => $request->nome,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'condominio' => $request->condominio,
                'tipo_contato' => $request->tipo_contato ?? 'orcamento',
                'mensagem' => $request->mensagem,
                'origem' => 'site',
                'status' => 'novo',
                'prioridade' => 2, // Prioridade média
                'data_primeiro_contato' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'referrer' => $request->headers->get('referer'),
                    'page_url' => $request->input('page_url'),
                ],
            ]);

            // Criar primeira interação
            Interacao::create([
                'lead_id' => $lead->id,
                'tipo' => 'nota',
                'assunto' => 'Lead criado via formulário do site',
                'descricao' => $request->mensagem,
                'data_interacao' => now(),
            ]);

            // Enviar email de notificação para administrador
            try {
                $adminEmail = config('mail.admin_notification_email', env('ADMIN_NOTIFICATION_EMAIL'));
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new NewLeadNotification($lead));
                    Log::info('Email de notificação enviado para administrador', ['lead_id' => $lead->id, 'email' => $adminEmail]);
                }
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de notificação', [
                    'lead_id' => $lead->id,
                    'error' => $e->getMessage()
                ]);
            }

            // Enviar email de confirmação para o lead
            try {
                Mail::to($lead->email)->send(new LeadConfirmation($lead));
                Log::info('Email de confirmação enviado para lead', ['lead_id' => $lead->id, 'email' => $lead->email]);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de confirmação', [
                    'lead_id' => $lead->id,
                    'error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Mensagem enviada com sucesso! Em breve entraremos em contato.',
                'data' => [
                    'id' => $lead->id,
                    'protocolo' => 'SFK-' . str_pad($lead->id, 6, '0', STR_PAD_LEFT)
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar solicitação. Por favor, tente novamente.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Listar leads com filtros
     */
    public function index(Request $request)
    {
        $query = Lead::with('interacoes');

        // Filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('origem')) {
            $query->where('origem', $request->origem);
        }

        if ($request->has('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telefone', 'like', "%{$search}%")
                  ->orWhere('condominio', 'like', "%{$search}%");
            });
        }

        // Ordenação
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = $request->input('order_dir', 'desc');
        $query->orderBy($orderBy, $orderDir);

        // Paginação
        $perPage = $request->input('per_page', 15);
        $leads = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $leads
        ]);
    }

    /**
     * Exibir lead específico
     */
    public function show($id)
    {
        $lead = Lead::with('interacoes')->find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead não encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $lead
        ]);
    }

    /**
     * Atualizar lead
     */
    public function update(Request $request, $id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead não encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'nullable|in:novo,contatado,em_negociacao,convertido,perdido,arquivado',
            'prioridade' => 'nullable|integer|min:1|max:3',
            'observacoes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $lead->update($request->only(['status', 'prioridade', 'observacoes', 'atribuido_para']));

        return response()->json([
            'success' => true,
            'message' => 'Lead atualizado com sucesso',
            'data' => $lead
        ]);
    }

    /**
     * Adicionar interação ao lead
     */
    public function addInteracao(Request $request, $id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead não encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tipo' => 'required|in:email,telefone,whatsapp,reuniao,visita,nota,outro',
            'assunto' => 'nullable|string|max:255',
            'descricao' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $interacao = Interacao::create([
            'lead_id' => $lead->id,
            'tipo' => $request->tipo,
            'assunto' => $request->assunto,
            'descricao' => $request->descricao,
            'data_interacao' => $request->data_interacao ?? now(),
            'usuario_id' => $request->usuario_id,
        ]);

        // Atualizar data do último contato
        $lead->registrarContato();

        return response()->json([
            'success' => true,
            'message' => 'Interação registrada com sucesso',
            'data' => $interacao
        ], 201);
    }
}
