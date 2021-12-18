<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response([
            'data' => Category::all()
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
            'name' => 'required'
        ]);

        $created = Category::query()->create([
            'name' => $request->get('name')
        ]);

        return response([
            'data' => $created
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return Response
     */
    public function show(Category $category): Response
    {
        return response([
            'data' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function update(Request $request, Category $category): Response
    {
         $request->validate([
            'name' => 'required'
        ]);

        $updated = $category->update([
            'name' => $request->input('name')
        ]);

        return response([
            'data' => $updated
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return Response
     */
    public function destroy(Category $category): Response
    {
        return response([
            'message' => $category->delete() . ' category deleted'
        ]);
    }
}
