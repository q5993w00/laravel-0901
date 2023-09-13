<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //顯示資料的地方 :
    public function index()
    {
        $products = Product::get();
        //產品列表頁(1)
        return view('product.cartlist', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //新增資料頁(2->產品新增頁)
        return view('product.addcartlist');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //驗證進來的資料是否合規龜龜龜規

        //新產品資料儲存

        //方法一
        //查看請求的資料(form表單裡input)
        $path = Storage::putFile('public/upload', $request->file('image'));

        //方法二
        // dd($request->all());
        Product::create([
            'name' => $request->name,
            'img_path' => str_replace('public', 'storage', $path),
            'price' => $request->price,
            'status' => $request->status,
            'desc' => $request->desc,
        ]);

        return redirect(route('product.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //新產品資料儲存功能

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::find($id);
        //產品編輯頁(3)
        return view('product.editcartlist',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        //驗證

        //產品更新功能
        // dd($request->all());
        $product = Product::find($id);
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'status' => $request->status,
            'desc' => $request->desc,
        ]);
        return redirect(route('product.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //刪除資料功能
    }
}
