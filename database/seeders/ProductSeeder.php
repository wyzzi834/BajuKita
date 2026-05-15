<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $imageName = 'no-image.jpeg';
        $imageDirectory = storage_path('app/public/image');

        File::ensureDirectoryExists($imageDirectory);

        if (! File::exists($imageDirectory . '/' . $imageName)) {
            File::copy(public_path('assets/no-image.jpeg'), $imageDirectory . '/' . $imageName);
        }

        $mensCategory = Categorie::updateOrCreate([
            'slug' => Str::slug('Baju Pria'),
        ], [
            'name' => 'Baju Pria',
        ]);

        $womensCategory = Categorie::updateOrCreate([
            'slug' => Str::slug('Baju Wanita'),
        ], [
            'name' => 'Baju Wanita',
        ]);

        $products = [
            [
                'category_id' => $mensCategory->id,
                'name' => 'Kemeja Flanel Kotak Hitam',
                'price' => '150000',
                'body' => 'Kemeja flanel pria motif kotak merah hitam berbahan lembut dan nyaman dipakai untuk gaya casual sehari-hari.',
            ],
            [
                'category_id' => $mensCategory->id,
                'name' => 'Kaos Polo Navy Pria',
                'price' => '125000',
                'body' => 'Kaos polo pria warna navy dengan bahan adem, cocok untuk tampilan santai maupun semi formal.',
            ],
            [
                'category_id' => $mensCategory->id,
                'name' => 'Jaket Denim Pria',
                'price' => '245000',
                'body' => 'Jaket denim pria dengan potongan regular fit yang mudah dipadukan dengan kaos dan celana casual.',
            ],
            [
                'category_id' => $mensCategory->id,
                'name' => 'Hoodie Basic Abu Pria',
                'price' => '185000',
                'body' => 'Hoodie pria warna abu dengan bahan fleece lembut, nyaman untuk aktivitas harian dan cuaca dingin.',
            ],
            [
                'category_id' => $mensCategory->id,
                'name' => 'Celana Chino Khaki Pria',
                'price' => '175000',
                'body' => 'Celana chino pria warna khaki dengan model slim fit, cocok untuk kerja, kuliah, atau hangout.',
            ],
            [
                'category_id' => $womensCategory->id,
                'name' => 'Blouse Putih Wanita',
                'price' => '135000',
                'body' => 'Blouse wanita warna putih dengan desain simpel dan rapi, cocok untuk kerja atau acara santai.',
            ],
            [
                'category_id' => $womensCategory->id,
                'name' => 'Dress Midi Floral Wanita',
                'price' => '220000',
                'body' => 'Dress midi wanita motif floral dengan bahan ringan dan jatuh, cocok untuk acara casual dan semi formal.',
            ],
            [
                'category_id' => $womensCategory->id,
                'name' => 'Cardigan Rajut Cream Wanita',
                'price' => '165000',
                'body' => 'Cardigan rajut wanita warna cream dengan tekstur lembut, cocok dipakai sebagai outer harian.',
            ],
            [
                'category_id' => $womensCategory->id,
                'name' => 'Rok Plisket Hitam Wanita',
                'price' => '145000',
                'body' => 'Rok plisket wanita warna hitam dengan pinggang elastis, mudah dipadukan dengan blouse atau sweater.',
            ],
            [
                'category_id' => $womensCategory->id,
                'name' => 'Tunik Casual Sage Wanita',
                'price' => '155000',
                'body' => 'Tunik wanita warna sage dengan potongan longgar dan nyaman untuk penggunaan sehari-hari.',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate([
                'slug' => Str::slug($product['name']),
            ], [
                'category_id' => $product['category_id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $imageName,
                'body' => $product['body'],
                'status' => 'publish',
            ]);
        }
    }
}
