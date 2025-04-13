<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatGptService
{
    /**
     * URL-ul endpoint-ului API OpenAI.
     *
     * Pentru modelul ChatGPT, de exemplu "gpt-3.5-turbo", folosiți endpoint-ul corespunzător.
     *
     * @var string
     */
    protected $apiUrl = 'https://api.openai.com/v1/chat/completions';

    /**
     * Cheia API OpenAI, extrasă din fișierul .env.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Constructorul clasei.
     */
    public function __construct()
    {
        $this->apiKey = config('services.chatgpt'); 
        // Alternativ, puteți folosi: env('OPENAI_API_KEY')
    }

    /**
     * Trimite un prompt către API și primește răspunsul.
     *
     * @param string $prompt Textul ce va fi trimis ca prompt.
     * @param array  $options Opțiuni suplimentare pentru payload (opțional).
     * @return string|null Răspunsul primit de la API sau null în caz de eroare.
     */
    public function ask(string $prompt, array $options = []): ?string
    {
        // Construim payload-ul conform cerințelor API-ului OpenAI
        $payload = array_merge([
            'model'    => 'gpt-4o-mini', // Puteți modifica modelul după necesitate
            'messages' => [
                [
                    'role'    => 'user',
                    'content' => $prompt,
                ]
            ],
        ], $options);

        // Facem call-ul API folosind HTTP facade din Laravel
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post($this->apiUrl, $payload);

        // Verificăm dacă cererea a reușit
        if ($response->successful()) {
            $data = $response->json();

            // Extragem răspunsul din array-ul returnat
            return $data['choices'][0]['message']['content'] ?? null;
        }

        // În caz de eroare, puteți loga eroarea sau face alte acțiuni necesare
        Log::error('Eroare API ChatGPT: ' . $response->body());
        return null;
    }

    /**
     * Generates the final prompt by combining a template from the configuration with user input,
     * and then calls the API to get personalized advice.
     *
     * @param string $userInput The input details provided by the user.
     * @param string $scenario  The key for the prompt template (e.g., "redistribution", "growth", "prioritization", "personalized").
     * @return string|null The advice from the AI or null in case of error.
     */
    public function generateTimeGoalAdvice(string $userInput, string $scenario = 'redistribution'): ?string
    {
        // Retrieve the appropriate prompt template from configuration
        $template = config("chat-gpt-prompts.time_goal.{$scenario}");
        if (!$template) {
            // If no template exists for the given scenario, log the issue and return null.
            Log::warning("No prompt template found for scenario: {$scenario}");
            return null;
        }

        // Combine the template and the user input to form the final prompt.
        $finalPrompt = $template . "\n\nHaving the template generated; here are the user details: " . $userInput . "\n\n" . config("chat-gpt-prompts.generation_keys");

        // Optionally, log the final prompt for debugging (remove in production if necessary)
        Log::info("Final Prompt: " . $finalPrompt);

        // Call the ask() method to send the prompt to the API and get the response.
        return $this->ask($finalPrompt);
    }
}
