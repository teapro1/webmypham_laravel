<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $sort = $request->get('sort', 'name_asc');
        $search = $request->get('search'); 
        $products = Product::query();
    
        if ($sort) {
            if ($sort == 'name_asc') {
                $products = $products->orderBy('name', 'asc');
            } elseif ($sort == 'name_desc') {
                $products = $products->orderBy('name', 'desc');
            } elseif ($sort == 'price_asc') {
                $products = $products->orderBy('price', 'asc');
            } elseif ($sort == 'price_desc') {
                $products = $products->orderBy('price', 'desc');
            } elseif ($sort == 'quantity_desc') {
                $products = $products->orderBy('quantity', 'desc');
            }
        }
    
     
        if ($search) {
            $products = $products->where('name', 'LIKE', '%' . $search . '%');
        }
    

        $productCount = $products->count(); 
    
        if (Auth::check() && Auth::user()->role === 'admin') {
            $products = $products->with('category')->paginate(10);
            return view('admin.products.index', compact('products'));
        } else {
            $category = Category::find($request->input('category_id'));
            $products = $category ? $category->products()->paginate(9) : $products->with('category')->paginate(9);
            return view('customer.products.index', compact('products', 'sort', 'search', 'productCount'));
        }
    }
    
    
    public function show(Product $product)
    {
        $product = Product::with('images', 'category')->findOrFail($product->id);
        session(['previous_url' => url()->previous()]); 
    
        if (Auth::check() && Auth::user()->role === 'admin') {
            return view('admin.products.show', compact('product'));
        } else {
            return view('customer.products.show', compact('product'));
        }
    }
    
    //admin them sp moi
    public function create()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            $categories = Category::all();
            return view('admin.products.create', compact('categories'));
        }

        return redirect('/')->with('error', 'Bạn không có quyền truy cập trang này.');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = new Product($request->only(['name', 'description', 'price', 'stock', 'category_id']));

        if ($request->hasFile('logo')) {
            $product->logo = $request->file('logo')->store('product_logos', 'public');
        }

        $product->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePath = $file->store('product_images', 'public');
                $product->images()->create(['image_path' => $imagePath]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Tạo mới sản phẩm thành công.');
    }

    
    public function edit(Product $product)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            $categories = Category::all();
            return view('admin.products.edit', compact('product', 'categories'));
        }

        return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này.');
    }


    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        
        $product->update($validatedData);

        if ($request->hasFile('logo')) {
            $product->logo = $request->file('logo')->store('logos', 'public');
        }

        $product->images()->delete();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('product_images', 'public');
                $product->images()->create(['image_path' => $imagePath]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }


    public function destroy(Product $product)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
        }

        return redirect('/')->with('error', 'Bạn không có quyền thực hiện hành động này.');
    }

    public function productsByCategory(Request $request, Category $category)
    {
        $sort = $request->input('sort', 'name_asc');
        $query = $category->products();
    
     
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }
    
        $products = $query->paginate(9); 
    
        return view('customer.products.index', compact('products', 'category', 'sort'));
    }
    
}
