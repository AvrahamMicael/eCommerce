<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        $products = cache()->remember('index', 60, fn() => Product::with('images')->get());
        $products->transform(fn($i, $k) => $i->formatPrice());
        return view('index', compact('products'));
    }

    public function productPage(int $id_product)
    {
        $product = cache()->remember("product-$id_product", 60*10, fn() => Product::with('images')->find($id_product));
        abort_if(! $product, 404);
        $product->formatPrice();
        return view('product_page', compact('product'));
    }
}