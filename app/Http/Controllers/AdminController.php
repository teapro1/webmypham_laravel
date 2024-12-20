<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function products()
    {
       
        $request = new Request();

        $productController = app(ProductController::class);

        return $productController->index($request);
    }

    public function categories()
    {
        $request = new Request();

        $categoryController = app(CategoryController::class);

        return $categoryController->index($request);
    }
}
