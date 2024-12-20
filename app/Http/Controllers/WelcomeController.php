<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\AdminMiddleware;
class WelcomeController extends Controller
{
    /**
     * Hiển thị trang chính của website.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::paginate(9);
        return view('welcome', compact('products'));
    }
}
