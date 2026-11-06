<?php

namespace App\Http\Controllers\API\v2;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $category = Category::where('status', 'Active')->get();
        $size = Size::where('status', 'Active')->get();
        $final = Product::join('categories', 'products.category_id', 'categories.id')
            ->select('products.id', 'products.name', 'products.category_id')
            ->where(['categories.status' => 'Active', 'products.status' => 'Active'])
            ->get();
        $variants = DB::table('product_size')
            ->join('products', 'products.id', 'product_size.product_id')
            ->join('categories', 'products.category_id', 'categories.id')
            ->join('sizes', 'sizes.id', 'product_size.size_id')
            ->where(['product_size.status' => 1, 'categories.status' => 'Active', 'products.status' => 'Active', 'sizes.status' => 'Active'])
            ->select('product_size.id', 'product_size.product_id', 'product_size.size_id', 'product_size.price as productprice', 'product_size.status')
            ->get();

        return response()->json(['category' => $category, 'product' => $final, 'size' => $size, 'items' => $variants]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
