<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Animal;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ApplicationResource;

class ApplicationController extends Controller
{
    // ADOTANTE: criar candidatura
    public function store(Request $request)
    {
        // 1. Validação do input
        $data = $request->validate([
            'animal_id' => 'required|exists:animals,id',
        ]);

        // 2. Regra de Negócio: Verificar disponibilidade do animal
        $animal = Animal::find($data['animal_id']);

        // O animal só pode ser adotado se estiver 'available'
        // Se o status for 'adopted' ou 'unavailable', bloqueamos
        if ($animal->status !== 'available') {
            return ApiResponse::error([
                'message' => 'Este animal já não está disponível para adoção. Status atual: ' . $animal->status
            ], 422);
        }

        // 3. Regra de Negócio: Limite de 3 candidaturas ativas (já tens)
        $activeApplicationsCount = Application::where('adopter_id', auth()->id())
            ->whereIn('status', ['submitted', 'under-review'])
            ->count();

        if ($activeApplicationsCount >= 3) {
            return ApiResponse::error(['message' => 'Limite de 3 candidaturas ativas atingido.'], 422);
        }

        // 4. Criação da candidatura
        return DB::transaction(function () use ($data) {
            $application = Application::create([
                'animal_id' => $data['animal_id'],
                'adopter_id' => auth()->id(),
                'status' => 'submitted'
            ]);

            return ApiResponse::success($application, null, 201);
        });
    }
    // ADOTANTE: listar as suas candidaturas
    public function myApplications()
    {
        $apps = Application::where('adopter_id', auth()->id())->get();
        return ApiResponse::success($apps);
    }

    // STAFF: listar todas as candidaturas
    public function index(Request $request)
    {
        // 1. Iniciamos a query base
        $query = Application::query();

        // 2. Filtro por status (ex: /api/applications?status=submitted)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // 3. Filtro por animal (ex: /api/applications?animal_id=5)
        if ($request->has('animal_id')) {
            $query->where('animal_id', $request->animal_id);
        }

        // 4. Eager Loading (importante para evitar N+1 queries)
        $query->with(['animal', 'adopter']);

        // 5. Paginação aplicada sobre a query já filtrada
        $applications = $query->paginate(10);

        // 6. Transformação com Resource
        $data = ApplicationResource::collection($applications);

        // 7. Objeto META
        $meta = [
            'total'        => $applications->total(),
            'current_page' => $applications->currentPage(),
            'last_page'    => $applications->lastPage(),
            'per_page'     => $applications->perPage(),
        ];

        return ApiResponse::success($data, $meta);
    }

    // STAFF: atualizar status
    public function updateStatus(Request $request, $id)
    {
        $application = Application::find($id);

        if (!$application) {
            return ApiResponse::error(['error' => 'Candidatura não encontrada'], 404);
        }

        $data = $request->validate([
            'status' => 'required|in:under-review,approved,rejected',
            'rejection_reason' => 'nullable|string'
        ]);

        // Regra: Rejeição obriga a motivo
        if ($data['status'] === 'rejected' && empty($data['rejection_reason'])) {
            return ApiResponse::error(['error' => 'Motivo da rejeição é obrigatório'], 422);
        }

        // Transação Atómica
        return DB::transaction(function () use ($application, $data) {
            
            // 1. Atualizar candidatura
            $application->update($data);

            // 2. Se for aprovada, marcar animal como adotado
            if ($data['status'] === 'approved') {
                $animal = $application->animal;
                $animal->update(['status' => 'adopted']);
            }

            return ApiResponse::success($application);
        });
    }
}
