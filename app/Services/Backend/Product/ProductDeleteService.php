<?php

namespace App\Services\Backend\Product;

use App\Repositories\ProductRepository;

/**
 * Class ProductDeleteService
 * @package App\Services
 */
class ProductDeleteService
{
    public function handle($id)
    {
        $product = (new ProductRepository)->getById($id);
        if (empty($product)) {
            # code...
            return redirect()->route('admin.product.index')->with('galat', 'Product Not Found');
        }

        unlink(public_path('storage/image/' . $product->image));
        $product->delete();

        return redirect()->route('admin.product.index')->with('success', 'Product Hass Been Deleted');
    }
}
