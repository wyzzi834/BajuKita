<?php

namespace App\Services\Backend\Product;

use App\Repositories\ProductRepository;

/**
 * Class ProductIndexService
 * @package App\Services
 */
class ProductIndexService
{
    public function handle()
    {
        $hitung = (new ProductRepository)->countStatus();
        return [
            'publish' => $hitung['publish'],
            'draft' => $hitung['draft'],
            'data' =>(new ProductRepository)->getAll()
        ];
    }
}
