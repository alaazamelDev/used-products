<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Like;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {

        // this parameter represent the search query
        $query = request('query');

        // collect inputs
        $name = request('name');
        $price = request('price');
        $category_id = request('category_id');
        $exp_date = request('exp_date');
        $user_id = request('user_id');

        // query parameters for products with price range
        $start_price = request('start_price');
        $end_price = request('end_price');

        // query parameters for products with expiry date range
        $start_date = request('start_date');
        $end_date = request('end_date');

        // get sort_by field, to sort the response according to it
        $sort_by = request('sort_by');

        // build query to fetch the required data
        $productQueryBuilder = Product::query();

        // search by name
        if ($name) {
            $productQueryBuilder->where('name', 'LIKE', '%' . $name . '%');
        }

        // search by category
        if ($category_id) {
            $productQueryBuilder->where('category_id', '=', $category_id);
        }

        // search by expiry date
        if ($exp_date) {
            $productQueryBuilder->where('exp_date', 'LIKE', '%' . $exp_date . '%');
        }

        if ($start_date) {
            $productQueryBuilder->where('exp_date', '>=', $start_date);
        }

        if ($end_date) {
            $productQueryBuilder->where('exp_date', '<=', $end_date);
        }

        // filter products by price range
        if ($start_price) {
            $productQueryBuilder->where('price', '>=', $start_price);
        }

        if ($end_price) {
            $productQueryBuilder->where('price', '<=', $end_price);
        }

        if ($price) {
            $productQueryBuilder->where('price', '=', $price);
        }

        // get products which belong to this user
        if ($user_id) {
            $productQueryBuilder->where('user_id', '=', $user_id);
        }

        // if sort_by field is exist, sort the response according to it
        if ($sort_by) {
            $productQueryBuilder->orderBy($sort_by);
        }

        // search in name and category and exp-date
        if ($query) {
            $productQueryBuilder
                ->where('name', 'LIKE', '%' . $query . '%')
                ->orWhere('exp_date', 'LIKE', '%' . $query . '%')
                ->orWhere('category', 'LIKE', '%' . $query . '%');
        }

        return response([
            'data' => $productQueryBuilder->get()
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
            'name' => ['required', 'max:40'],
            'image_url' => 'required',
            'exp_date' => 'required',
            'phone_number' => 'required',
            'quantity' => 'nullable',
            'price' => 'required',
            'discount_list' => 'required',
            'category_id' => 'required',
        ]);

        // create a new entry in the database
        $product = Product::query()->create([
            'name' => $request->get('name'),
            'image_url' => $request->get('image_url'),
            'exp_date' => $request->get('exp_date'),
            'phone_number' => $request->get('phone_number'),
            'description' => $request->get('description'),
            'quantity' => $request->get('quantity'),
            'price' => $request->get('price'),
            'category_id' => $request->get('category_id'),
            'user_id' => $request->user()->id,
        ]);

        // store discounts
        $discount_list = json_decode($request->get('discount_list'));
        foreach ($discount_list as $discount) {
            Discount::query()->create([
                'date' => $discount->date,
                'discount_percentage' => $discount->discount_percentage,
                'product_id' => $product->id,
            ]);
        }

        // save editing
        $product->save();


        // Returns the created product object
        return response($product);
    }


    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return Response
     */
    public function show(Product $product): Response
    {
        return response($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function update(Request $request, Product $product): Response
    {
        // check if user is the owner of this product
        if ($request->user()->id != $product->user->id)
            return response([
                'message' => 'you can\'t update a product you don\'t own'
            ], 401);

        // collect inputs
        $name = $request->input('name');
        $image_url = $request->input('image_url');
        $price = $request->input('price');
        $phone_number = $request->input('phone_number');
        $description = $request->input('description');
        $quantity = $request->input('quantity');
        $category_id = $request->input('category_id');
        $views = $request->input('views');

        // check name if it's passed or no
        if ($name) {
            $product->name = $name;
        }

        // check image_url if it's passed or no
        if ($image_url) {
            $product->image_url = $image_url;
        }

        // check price if it's passed or no
        if ($price) {
            $product->price = $price;
        }

        // check phone_number
        if ($phone_number) {
            $product->phone_number = $phone_number;
        }

        // check description
        if ($description) {
            $product->description = $description;
        }

        // check quantity
        if ($quantity) {
            $product->quantity = $quantity;
        }

        // check category_id
        if ($category_id) {
            $product->category_id = $category_id;
        }

        // check views
        if ($views) {
            $product->views = $views;
        }

        // save the new version of product
        $updated = $product->save();
        return response([
            'message' => $updated ? 'updated successfully' : 'validate your data'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return Response
     */
    public function destroy(Product $product): Response
    {
        // check if user is the owner of this product
        if (Auth::id() != $product->user->id)
            return response([
                'message' => 'you can\'t delete a product you don\'t own'
            ], 401);

        return response([
            'message' => $product->delete() . ' product deleted'
        ]);
    }

    public function isLikedByMe(Product $product): Response
    {
        if (Like::query()
            ->where('user_id', '=', Auth::id())
            ->where('product_id', '=', $product->id)
            ->exists()) {
            return response([
                'isLiked' => true
            ]);
        }
        return response([
            'isLiked' => false
        ]);
    }

    public function like(Product $product)
    {
        // to know whether there is an like already or not
        $existing_like = Like::withTrashed()
            ->where('product_id', '=', $product->id)
            ->where('user_id', '=', Auth::id())
            ->first();

        if (is_null($existing_like)) {
            Like::query()->create([
                'product_id' => $product->id,
                'user_id' => Auth::id()
            ]);
        } else {
            if (is_null($existing_like->deleted_at)) {
                $existing_like->delete();
            } else {
                $existing_like->restore();
            }
        }
    }

    public function increaseViews(Product $product)
    {
        $product->views += 1;
        $product->save();
        return response([
            'message' => 'increased'
        ]);
    }
}
