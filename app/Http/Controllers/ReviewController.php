<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response([
            'data' => Review::all(),
            'message' => 'data retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'content' => 'required|max:100',
            'user_id' => 'required',
            'product_id' => 'required',
        ]);

        $review = Review::query()->create([
            'content' => $request->get('content'),
            'user_id' => $request->get('user_id'),
            'product_id' => $request->get('product_id'),
        ]);
        return response($review);
    }

    /**
     * Display the specified resource.
     *
     * @param Review $review
     * @return Response
     */
    public function show(Review $review): Response
    {
        return response([
            'data' => $review
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Review $review
     * @return Response
     */
    public function update(Request $request, Review $review): Response
    {
        // check if user is the owner of this product
        if ($request->user()->id != $review->user->id)
            return response([
                'message' => 'you can\'t update a review you don\'t own'
            ], 401);

        $content = $request->input('content');
        if ($content) {
            $review->content = $content;
        }

        // save the new version
        $updated = $review->push();

        return response([
            'message' => $updated ? ' review updated successfully' : 'validate your data'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Review $review
     * @return Response
     */
    public function destroy(Review $review): Response
    {
        // check if user is the owner of this product
        if (Auth::id() != $review->user->id)
            return response([
                'message' => 'you can\'t delete a review you don\'t own'
            ], 401);

        return response([
            'message' => $review->delete() . ' review deleted'
        ]);
    }
}
