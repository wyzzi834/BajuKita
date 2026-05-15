<?php

namespace App\Repositories;

use App\Models\Categorie;
use App\Models\Product;

/**
 * Class ProductRepository.
 */
class ProductRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function getAll()
    {
        return Product::with('category')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*')
            ->orderByRaw("
                CASE
                    WHEN categories.name = 'Baju Pria' THEN 1
                    WHEN categories.name = 'Kemeja Pria' THEN 1
                    WHEN categories.name = 'Baju Wanita' THEN 2
                    ELSE 3
                END
            ")
            ->orderBy('products.created_at')
            ->paginate(5);
    }

    public function countStatus()
    {
        return [
            'publish' => count(Product::where('status', 'publish')->get()),
            'draft' => count(Product::where('status', 'draft')->get())
        ];
    }

    public function searchKategori($request)
    {
        $search = $request->q;
        return Categorie::select("id", "name")
            ->where('name', 'LIKE', "%$search%")
            ->get();
    }

    public function store($data)
    {
        return Product::create($data);
    }

    public function getById($id)
    {
        return Product::with('category')->find($id);
    }

    public function update($data, $id)
    {
        return $this->getById($id)->update($data);
    }
}
