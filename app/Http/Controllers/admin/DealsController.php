<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Report;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function dealsList(Request $request)
    {
        $query = Post::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deals = $query->orderBy('created_at', 'desc')->paginate(8);
        return view('backend.deals.dealsList', compact('deals'));
    }


    public function reportsList(Request $request)
    {
        $query = Report::query();

        $query->where('reportable_type', Post::class);

        if ($request->filled('search')) {
            $query->where('reason', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->with(['user', 'reportable'])->orderBy('created_at', 'desc')->paginate(10);
        return view('backend.deals.reportsList', compact('reports'));
    }

    public function viewDeal()
    {
        $post = (object) [
            'id' => 1,
            'title' => 'Amazing Deal on Laptops!',
            'description' => 'Check out this amazing deal on laptops with discounts up to 40%! Grab yours now!',
            'link' => 'https://example.com/laptop-deal',
            'upvotes' => 200,
            'downvotes' => 5,
            'posted_at' => Carbon::now(),
            'comment_count' => 4,
            'category' => 'Electronics',
            'verified_member' => 1,
            'image' => 'laptop-deal.jpg',
            'discount_text' => '40% off',
            'price_saving' => '$300',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'status' => 1,
            'user' => (object) [
                'name' => 'John Doe'
            ],
            'comments' => [
                (object) [
                    'user' => (object) ['name' => 'Alice'],
                    'body' => 'I just bought this laptop! The deal is amazing!',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'comments' => [
                        (object) [
                            'user' => (object) ['name' => 'Bob'],
                            'body' => 'I agree, the specs are great for the price!',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'comments' => [
                                (object) [
                                    'user' => (object) ['name' => 'Charlie'],
                                    'body' => 'I was skeptical, but after reading reviews, I’m going to get it!',
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                    'comments' => [
                                        (object) [
                                            'user' => (object) ['name' => 'Dave'],
                                            'body' => 'I have been using it for a month now. Totally worth it!',
                                            'created_at' => Carbon::now(),
                                            'updated_at' => Carbon::now(),
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        (object) [
                            'user' => (object) ['name' => 'Emma'],
                            'body' => 'Is the battery life really that long?',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'comments' => [
                                (object) [
                                    'user' => (object) ['name' => 'Frank'],
                                    'body' => 'Yes, it lasts for about 12 hours with regular use.',
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]
                            ]
                        ]
                    ]
                ],
                (object) [
                    'user' => (object) ['name' => 'Daniel'],
                    'body' => 'I’m thinking of buying this. Is the delivery fast?',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'comments' => []
                ],
                (object) [
                    'user' => (object) ['name' => 'Sophia'],
                    'body' => 'Anyone knows if there is an additional discount on students?',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'comments' => [
                        (object) [
                            'user' => (object) ['name' => 'Grace'],
                            'body' => 'You can get an additional 10% off with a student ID!',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]
                    ]
                ]
            ]
        ];


        return view('backend.deals.viewDeals', compact('post'));
    }

    public function updateStatus($id)
    {
        $deal = Post::findOrFail($id);
        $deal->status = !$deal->status;
        $deal->save();

        return back()->with('success', 'Deal status updated successfully.');
    }

    public function updateReportStatus($id)
    {
        $report = Report::findOrFail($id);
        $report->status = 'reviewed';
        $report->save();

        return back()->with('success', 'Report status updated successfully.');
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('backend.deals.viewDeals', compact('post'));
    }

    public function edit($id)
    {
        $deal = Post::findOrFail($id);
        return view('deals.edit', compact('deal'));
    }

    public function destroy($id)
    {
        $deal = Post::findOrFail($id);
        $deal->delete();

        return back()->with('success', 'Deal deleted successfully.');
    }

    public function destroyReport($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return back()->with('success', 'Report deleted successfully.');
    }

    public function deactivateDeal($report, $deal)
    {

        try {

            $deal = Post::findOrFail($deal);
            $deal->status = 0;
            $deal->save();

            $report = Report::findOrFail($report);
            $report->status = 'resolved';
            $report->save();


            return back()->with('success', 'Deal deactivated successfully.');
        } catch (Exception $ex) {
            Log::error("Failed to deactivate deal ID {$deal} or update report ID {$report}. Error: " . $ex->getMessage());

            return back()->with('error', 'Deal deactivation failed.');
        }
    }
}
