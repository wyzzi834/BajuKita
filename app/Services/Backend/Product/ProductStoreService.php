<?php

namespace App\Services\Backend\Product;

use App\Repositories\ProductRepository;

/**
 * Class ProductStoreService
 * @package App\Services
 */
class ProductStoreService
{
    public function handle($request)
    {
        $request->validate([
            'title' => 'required',
            'harga' => 'required',
            'status' => 'required',
            'kategori' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:5120',
            'desc' => 'required'
        ], [
            'image.max' => 'Ukuran gambar maksimal 5 MB.',
            'image.mimes' => 'Gambar harus berformat PNG, JPG, atau JPEG.',
            'image.uploaded' => 'Gambar gagal diupload. Pastikan ukuran file maksimal 5 MB.',
        ]);

        $file = $request->file('image');
        $imageName = time() . '.' . $file->extension();

        $data = [
            'category_id' => $request->kategori,
            'name' => $request->title,
            'price' => $request->harga,
            'body' => $request->desc,
            'image' => $imageName,
            'status' => $request->status,
            'slug' => str_replace(' ', '-', $request->title)
        ];

        $response =  (new ProductRepository)->store($data);

        $file->storeAs('public/image/', $imageName);

        return redirect()->route('admin.product.index')->with('success', 'Product Hass Been Added');
    }
}
