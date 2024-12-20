<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('products')->paginate(6);
    
        if (Auth::check() && Auth::user()->role === 'admin') {
            return view('admin.categories.index', compact('categories'));
        }
    
        return view('customer.categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $category = new Category();
        $category->name = $request->name;
    
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public'); 
            $category->logo = $logoPath;
        }
    
        $category->save();
    
        return redirect()->route('admin.categories.index')->with('success', 'Tạo mới danh mục thành công.');
    }
    
    public function show(Category $category, Request $request)
    {
        $sort = $request->input('sort', 'name_asc');
    
        $products = $category->products();
    
        switch ($sort) {
            case 'name_asc':
                $products->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $products->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $products->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $products->orderBy('price', 'desc');
                break;
            default:
                $products->orderBy('name', 'asc');
                break;
        }
    
    
        $products = $products->paginate(9);
    
        if (Auth::check() && Auth::user()->role === 'admin') {
            session(['admin_category_url' => url()->current()]);
            $products = $category->products()->paginate(10);
            return view('admin.categories.show', compact('category', 'products'));
        }
    
        return view('customer.products.index', compact('category', 'products', 'sort'));
    }
    
    
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = Category::findOrFail($id);
        $categoryData = $request->only('name');

        if ($request->hasFile('logo')) {
            if ($category->logo) {
                Storage::delete('public/' . $category->logo); 
            }

            $logoPath = $request->file('logo')->store('logos', 'public'); 
            $categoryData['logo'] = $logoPath;
        }

        $category->update($categoryData);

        return redirect()->route('admin.categories.index')->with('success', 'Sửa danh mục thành công.');
    }

    public function destroy(Category $category)
    {
       
        if ($category->logo) {
            Storage::delete('public/' . $category->logo); 
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Xoá danh mục thành công.');
    }
}
