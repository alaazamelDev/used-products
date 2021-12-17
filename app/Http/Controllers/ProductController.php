<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {

        $validated = $request->validate([
            'name' => ['required', 'max:40'],
            'image_url' => 'required',
            'exp_date' => 'required',
            'phone_number' => 'required',
            'views' => 'nullable',
            'quantity' => 'nullable',
            'price' => 'required',
            'category_id' => 'required',
        ]);

        // create a new entry in the database
        $product = Product::query()->create([
            'name' => $request->get('name'),
            'image_url' => $request->get('image_url'),
            'exp_date' => Carbon::parse($request->get('exp_date'))->format('Y-m-d H:i:s'),
            'phone_number' => $request->get('phone_number'),
            'description' => $request->get('description'),
            'views' => $request->get('views'),
            'quantity' => $request->get('quantity'),
            'price' => $request->get('price'),
            'category_id' => $request->get('category_id'),
            'user_id' => $request->user()->id,
        ]);

        // Returns the created product object
        return response($product);
    }


    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return Response
     */
    public function show(Product $product)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
