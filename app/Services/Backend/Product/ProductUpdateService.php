<?php

namespace App\Services\Backend\Product;

use App\Repositories\ProductRepository;

/**
 * Class ProductUpdateService
 * @package App\Services
 */
class ProductUpdateService
{
    public function handle($request, $id)
    {
        $request->validate([
            'title' => 'required',
            'harga' => 'required',
            'status' => 'required',
            'kategori' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg|max:5120',
            'desc' => 'required'
        ], [
            'image.max' => 'Ukuran gambar maksimal 5 MB.',
            'image.mimes' => 'Gambar harus berformat PNG, JPG, atau JPEG.',
            'image.uploaded' => 'Gambar gagal diupload. Pastikan ukuran file maksimal 5 MB.',
        ]);

        $product = (new ProductRepository)->getById($id);
        if (empty($product)) {
            # code...
            return redirect()->route('admin.product.index')->with('galat', 'Product Not Found');
        }

        if ($request->image) {
            # code...
            $oldImage = public_path('storage/image/' . $product->image);
            if ($product->image !== 'no-image.jpeg' && file_exists($oldImage)) {
                unlink($oldImage);
            }

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

            (new ProductRepository)->update($data, $id);

            $file->storeAs('public/image/', $imageName);
        } else {
            # code...
            $data = [
                'category_id' => $request->kategori,
                'name' => $request->title,
                'price' => $request->harga,
                'body' => $request->desc,
                'status' => $request->status,
                'slug' => str_replace(' ', '-', $request->title)
            ];

            (new ProductRepository)->update($data, $id);
        }

        return redirect()->route('admin.product.index')->with('success', 'Product Hass Been Update');
    }
}
