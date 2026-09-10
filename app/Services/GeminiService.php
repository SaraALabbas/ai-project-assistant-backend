<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    public function analyzeProject($description)
    {
        $apiKey = env('GEMINI_API_KEY');

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent';

        $prompt = '
You are an AI Project Assistant.

Analyze the user project description carefully.

First, identify the type of project.

The project can be:
- Website
- Web Application
- Mobile Application
- Desktop Application
- AI Application
- E-commerce
- or another suitable type.

Do NOT assume that every project is a website.

Recommend technologies based on the actual project requirements.

For mobile applications, consider technologies such as Flutter or React Native.

For web applications, consider technologies such as React, Vue, Angular, Laravel, Node.js, or other suitable technologies.

For desktop applications, recommend suitable desktop technologies.

Return ONLY valid JSON with exactly these fields:

{
    "project_type": "",
    "technologies": [],
    "pages": [],
    "features": [],
    "user_roles": [],
    "database_entities": [],
    "estimated_time": "",
    "difficulty": "",
    "difficulty_score": 0,
    "next_steps": []
}

Rules:
- difficulty must be Easy, Medium, or Hard.
- difficulty_score must be 1 for Easy, 3 for Medium, and 5 for Hard.
- technologies must contain suitable technologies for this specific project.
- pages should contain the main screens or pages.
- features should contain the main functionality.
- user_roles should contain the different types of users.
- database_entities should contain the main data entities.
- estimated_time should be an approximate development time.
- next_steps should contain practical steps to start building the project.

Project description:

' . $description;

        $response = Http::timeout(60)
            ->withHeaders([
                'x-goog-api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt
                            ]
                        ]
                    ]
                ]
            ]);

        $data = $response->json();

        $text = $data['candidates'][0]['content']['parts'][0]['text'];

        $text = trim($text);

        $text = preg_replace('/^```json/', '', $text);
        $text = preg_replace('/\s*```$/', '', $text);

        return json_decode($text, true);
    }
}