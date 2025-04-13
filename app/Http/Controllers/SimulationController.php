<?php

namespace App\Http\Controllers;

use App\Services\ChatGptService;
use Illuminate\Http\Request;

class SimulationController extends Controller
{
    protected $chatGptService;

    public function __construct(ChatGptService $chatGptService)
    {
        $this->chatGptService = $chatGptService;
    }

    public function getAdvice(Request $request)
    {
        // Assume the user's detailed information is passed via 'user_input'
        $userInput = $request->input('user_input', 'Default user input details...');
        $scenario  = $request->input('scenario', 'redistribution'); // Default scenario

        // Choose the scenario you want to use, for example 'redistribution'
        $advice = $this->chatGptService->generateTimeGoalAdvice($userInput, $scenario);

        // Return the advice as JSON or pass it to a view
        return response()->json(['advice' => $advice]);
    }
}
