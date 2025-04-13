<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChatGptService;

class DashboardController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Process the form submission, generate AI advice, and return to the dashboard.
     */
    public function getAdvice(Request $request, ChatGptService $chatGptService)
    {
        // Retrieve the selected scenario from the form
        $scenario = $request->input('scenario');

        // Optionally, get additional details provided by the user
        $userInput = $request->input('user_input', ''); 

        // Call your ChatGptService to generate advice based on the user input and scenario
        $advice = $chatGptService->generateTimeGoalAdvice($userInput, $scenario);

        // Redirect back with the generated advice (using session flash data)
        return redirect()->back()->with('advice', $advice);
    }
}
