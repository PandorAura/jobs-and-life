<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Challenge;

class ChallengesTableSeeder extends Seeder
{
    public function run()
    {
        $challenges = [
            [
                'month' => 1,
                'title' => 'No Coffee Challenge',
                'description' => 'Avoid buying coffee outside for the entire month'
            ],
            [
                'month' => 2,
                'title' => 'Cash-Only February',
                'description' => 'Use only physical cash for all purchases'
            ],
            [
                'month' => 3,
                'title' => 'Subscription Cleanse',
                'description' => 'Cancel at least 3 unused subscriptions'
            ],
            [
                'month' => 4,
                'title' => 'No Impulse Buys',
                'description' => 'Implement a 48-hour waiting period for all non-essential purchases'
            ],
            [
                'month' => 5,
                'title' => 'Meal Prep Master',
                'description' => 'Prepare all meals at home (no takeout/delivery)'
            ],
            [
                'month' => 6,
                'title' => 'Half-Price Summer',
                'description' => 'Spend 50% less on entertainment compared to last month'
            ],
            [
                'month' => 7,
                'title' => 'Credit Card Free July',
                'description' => 'Use only debit cards or cash'
            ],
            [
                'month' => 8,
                'title' => 'Utility Savings',
                'description' => 'Reduce electricity/water usage by 20%'
            ],
            [
                'month' => 9,
                'title' => 'No New Clothes',
                'description' => 'Avoid buying any new clothing items'
            ],
            [
                'month' => 10,
                'title' => 'Pantry Challenge',
                'description' => 'Cook primarily from pantry staples before buying more'
            ],
            [
                'month' => 11,
                'title' => 'Black Friday Resistance',
                'description' => 'No impulse purchases during sales events'
            ],
            [
                'month' => 12,
                'title' => 'Gift Budgeting',
                'description' => 'Set and stick to a holiday gift budget'
            ]
        ];

        foreach ($challenges as $challenge) {
            Challenge::create($challenge);
        }
    }
}