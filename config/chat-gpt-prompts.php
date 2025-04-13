<?php

return [
    'time_goal' => [
        //'prompt' => "You are a financial advisor. You are given a user's financial situation and a new goal. You need to provide advice on how to achieve the goal while maintaining the user's other financial goals.", 

        'redistribution' => "Here is a template for example: User has allocated savings as follows: 20% for a car, 10% for an emergency fund, and 70% for housing expenses. Now the user wishes to add savings for an Europe Trip. How should they redistribute these percentages while maintaining essential goals (car, emergency fund, and housing) and still allocate a reasonable portion for the Europe Trip? Provide concrete suggestions with example percentages and clear financial justifications.",
        
        'growth' => "The user is currently saving monthly according to this allocation: 20% for a car, 10% for an emergency fund, and 70% for housing expenses. They want to add a new objective: savings for an Europe Trip. If increasing the overall savings amount is possible without affecting the existing allocations, what financial strategy would you recommend to incorporate Europe Trip savings? Suggest a method to increase the total monthly savings and provide an ideal distribution of the amounts, explaining the benefits of this approach.",
        
        'prioritization' => "Given the current savings distribution of 20% for a car, 10% for an emergency fund, and 70% for housing expenses, the user wants to add savings for an Europe Trip but is concerned about compromising financial security. Present two options: (1) slightly reduce the existing percentages to create room for Europe Trip savings, and (2) increase the overall monthly savings by optimizing expenses or securing additional income. Explain the pros and cons of each option in detail, providing specific recommendations on percentage adjustments or strategies to increase the savings budget.",
        
        'personalized' => "Based on the following financial situation: monthly income of X, with savings allocated as 20% for a car, 10% for an emergency fund, and 70% for housing expenses, and an additional goal to save for an Europe Trip, provide personalized recommendations on how the user can reorganize their budget to achieve all goals without compromising financial stability. Include practical tactics for reducing expenses or methods to boost income if necessary."
    ],

    'generation_keys' => '
    You are not allowed to start the generation with a starting phrase!;
    '
];
