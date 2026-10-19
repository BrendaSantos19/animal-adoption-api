<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Responses\ApiResponse;

class ReportController extends Controller
{
    public function adoptions(Request $request)
    {
        // 1. Ler filtros opcionais
        $start = $request->query('start_date');
        $end = $request->query('end_date');
        $speciesFilter = $request->query('species');

        // 2. Query base: candidaturas aprovadas + segurança de SoftDeletes do animal
        $query = Application::where('applications.status', 'approved')
            ->join('animals', 'applications.animal_id', '=', 'animals.id')
            ->whereNull('animals.deleted_at'); //ignora animais eliminados via soft delete

        if ($start) {
            $query->whereDate('applications.updated_at', '>=', $start);
        }

        if ($end) {
            $query->whereDate('applications.updated_at', '<=', $end);
        }

        if ($speciesFilter) {
            $query->where('animals.species', $speciesFilter);
        }

        // Clonar query para reutilizar com as agregações de forma limpa
        $baseQuery = clone $query;

        // 3. Total de adoções no período
        $totalAdoptions = $query->count('applications.id');

        // 4. Adoções por espécie
        $bySpecies = (clone $baseQuery)
            ->select('animals.species', DB::raw('COUNT(*) as count'))
            ->groupBy('animals.species')
            ->get();

        // 5. Adoções por mês
        $byMonth = (clone $baseQuery)
            ->select(DB::raw("strftime('%Y-%m', applications.updated_at) as month"), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 6. Taxa de adoção baseada apenas nos animais ativos
        $totalAnimals = Animal::count(); // Já ignora soft deletes automaticamente por Eloquent
        $adoptionRate = $totalAnimals > 0
            ? round(($totalAdoptions / $totalAnimals) * 100, 2)
            : 0;

        // 7. Tempo médio até adoção (Dias entre a criação do registo do animal e a aprovação)
        $avgDays = (clone $baseQuery)
            ->select(DB::raw("AVG(julianday(applications.updated_at) - julianday(animals.created_at)) as avg_days"))
            ->value('avg_days');

        $avgDays = $avgDays ? round($avgDays, 1) : 0;

        // 8. Resposta final padronizada
        return ApiResponse::success([
            'filters' => [
                'start_date' => $start,
                'end_date' => $end,
                'species' => $speciesFilter
            ],
            'summary' => [
                'total_adoptions' => $totalAdoptions,
                'adoption_rate_percent' => $adoptionRate,
                'average_days_until_adoption' => $avgDays
            ],
            'by_species' => $bySpecies,
            'by_month' => $byMonth
        ]);
    }
}

