<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\FileService;

class ProductController extends Controller
{
    //寫法一
    // protected $FileService;
    // public function __construct(FileService $FileService)
    // {
    //     $this->FileService = $FileService;
    // }
    //寫法2  (與上面功用相同)
    public function __construct(protected FileService $fileService)
    {
    }

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
        //驗證進來的資料是否合規

        //新產品資料儲存

        //方法一 使用Storage
        // $path = Storage::putFile('public/upload', $request->file('image'));

        //查看請求的資料(form表單裡input)
        //這段是做圖片儲存的動作

        //方法二使用fileService
        $path = $this->fileService->imgUpload($request->file('image'),'products-image');


        // dd($request->all());
        Product::create([
            'name' => $request->name,
            //方法一
            // 'img_path' => str_replace('public', 'storage', $path),
            //方法二
            'img_path' => $path,

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
        return view('product.editcartlist', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
            $product = Product::find($id);
        // 方法一
        //驗證
    //    // 如果有上傳圖片
    //     if ($request->file('image')) {
    //         // 上傳新圖片並獲取新圖片的路徑
    //         $path = Storage::putFile('public/upload', $request->file('image'));
    //         // 刪除舊圖片
    //         Storage::delete(str_replace('storage', 'public', $product->img_path));

    //         // 更新產品信息，包括名稱、價格、狀態、描述和新圖片路徑
    //         $product->update([
    //             'name' => $request->name,
    //             'price' => $request->price,
    //             'status' => $request->status,
    //             'desc' => $request->desc,
    //             'img_path' => str_replace('public', 'storage', $path),
    //         ]);
    //     } else {
    //  // 如果沒有上傳新圖片，僅更新產品信息（名稱、價格、狀態、描述）
    //         // dd($request->all());
    //         $product->update([
    //             'name' => $request->name,
    //             'price' => $request->price,
    //             'status' => $request->status,
    //             'desc' => $request->desc,
    //         ]);
    //     }



        if ($request->hasFile('image')) {
            $path = $this->fileService->imgUpload($request->file('image'),'products-image');
            $this->fileService->deleteUpload($product->img_path);
            $product->update([
                            'name' => $request->name,
                            'price' => $request->price,
                            'status' => $request->status,
                            'desc' => $request->desc,
                            'img_path' => $path,
                        ]);

        }else{
            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'status' => $request->status,
                'desc' => $request->desc,
            ]);
        }
         // 重定向回產品列表頁
        return redirect(route('product.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $this->fileService->deleteUpload($product->img_path);
        $product->delete();

        return redirect(route('product.index'));
        //刪除資料功能
        // dd($id);
    }
}
