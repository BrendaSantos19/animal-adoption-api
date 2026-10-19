<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use App\Http\Resources\AnimalResource;

class AnimalController extends Controller
{
    // LISTAR TODOS OS ANIMAIS (com filtros opcionais)
    public function index(Request $request)
    {
        // 1. Começamos com a Query Builder, não com o modelo direto
        $query = Animal::query();

        // 2. Aplicamos filtros opcionais se existirem no request
        if ($request->has('species')) {
            $query->where('species', $request->species);
        }

        if ($request->has('size')) {
            $query->where('size', $request->size);
        }

        if ($request->has('age')) {
            $query->where('age', $request->age);
        }

        // 3. Paginação aplicada sobre a query já filtrada
        $animals = $query->paginate(10);

        // 4. Transformação com Resource
        $data = AnimalResource::collection($animals);

        // 5. Metadados
        $meta = [
            'total'        => $animals->total(),
            'current_page' => $animals->currentPage(),
            'last_page'    => $animals->lastPage(),
            'per_page'     => $animals->perPage(),
        ];

        return ApiResponse::success($data, $meta);
    }
    // CRIAR ANIMAL
   public function store(Request $request)
    {
        $data = $request->validate([
            'species' => 'required|string',
            'breed' => 'nullable|string',
            'age' => 'required|integer',
            'size' => 'required|in:small,medium,large', 
            'status' => 'required|in:available,unavailable,adopted' 
        ]);

        $animal = Animal::create($data);

        return ApiResponse::success($animal, null, 201);
    }
    // MOSTRAR UM ANIMAL
    public function show($id)
    {
        $animal = Animal::find($id);

        if (!$animal) {
            return ApiResponse::error(['error' => 'Animal não encontrado'], 404);
        }

        return ApiResponse::success($animal);
    }
    // ATUALIZAR ANIMAL
    public function update(Request $request, $id)
    {
        $animal = Animal::find($id);

        if (!$animal) {
            return ApiResponse::error(['error' => 'Animal não encontrado'], 404);
        }

        $data = $request->validate([
            'species' => 'sometimes|string',
            'breed' => 'sometimes|string|nullable',
            'age' => 'sometimes|integer',
            'size' => 'sometimes|in:small,medium,large', 
            'status' => 'sometimes|in:available,unavailable,adopted'
        ]);

        $animal->update($data);

        return ApiResponse::success($animal);
    }
    // APAGAR ANIMAL
    public function destroy($id)
    {
        $animal = Animal::find($id);

        if (!$animal) {
            return ApiResponse::error(['error' => 'Animal não encontrado'], 404);
        }

        $animal->delete();

        return ApiResponse::success(['message' => 'Animal removido'], null, 200);
    }
}
