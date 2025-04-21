<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DealsController extends Controller
{
    public function codeGenerator()
    {
        return view('backend.aiapplication/codeGenerator');
    }

    public function addDeal()
    {
        return view('backend.deals.addDeal');
    }

    public function dealsList()
    {
        return view('backend.deals.dealsList');
    }

    public function viewDeal()
    {
        $deal = (object) [
            'title' => '🔥 50% Off on Wireless Headphones!',
            'description' => 'These headphones are now just $49.99. High quality sound and noise cancellation included. Don’t miss out!',
            'image' => 'headphones.jpg',
            'link' => 'https://example.com/deal/wireless-headphones',
            'created_at' => now()->subDays(1),
            'votes_count' => 34,
            'user' => (object) [
                'name' => 'John Doe'
            ],
            'category' => (object) [
                'name' => 'Electronics'
            ],
            'comments' => [
                (object) [
                    'body' => 'I just bought it. Awesome quality!',
                    'created_at' => now()->subHours(2),
                    'user' => (object) ['name' => 'Alice']
                ],
                (object) [
                    'body' => 'Is it compatible with Android?',
                    'created_at' => now()->subHours(4),
                    'user' => (object) ['name' => 'Bob']
                ],
            ],
        ];

        return view('backend.deals.viewDeals', compact('deal'));
    }

}
