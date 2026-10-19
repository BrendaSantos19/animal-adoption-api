<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Http\Responses\ApiResponse;

class AnimalApplicationController extends Controller
{
    public function index($animalId)
    {
        $apps = Application::where('animal_id', $animalId)->paginate(10);

        return ApiResponse::success($apps);
    }
}
