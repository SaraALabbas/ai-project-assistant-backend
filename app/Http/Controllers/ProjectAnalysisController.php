<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\Request;

class ProjectAnalysisController extends Controller
{
    public function analyze(Request $request, GeminiService $geminiService)
    {
        $description = $request->input('projectDescription');

        $result = $geminiService->analyzeProject($description);

        return response()->json($result);
    }
}