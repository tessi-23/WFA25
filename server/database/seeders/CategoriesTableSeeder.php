<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kategorie 1: KWM
        $category1 = new Category;
        $category1->title = 'KWM';
        $category1->description = 'Kommunikation, Wissen, Medien';
        $category1->created_at = date("Y-m-d H:i:s");
        $category1->updated_at = date("Y-m-d H:i:s");
        $category1->save();

        // Kategorie 2: MTD
        $category2 = new Category;
        $category2->title = 'MTD';
        $category2->description = 'Medientechnik und Design';
        $category2->created_at = date("Y-m-d H:i:s");
        $category2->updated_at = date("Y-m-d H:i:s");
        $category2->save();

        // Kategorie 3: SE
        $category3 = new Category;
        $category3->title = 'SE';
        $category3->description = 'Software Engineering';
        $category3->created_at = date("Y-m-d H:i:s");
        $category3->updated_at = date("Y-m-d H:i:s");
        $category3->save();

        // Kategorie 4: HSD
        $category4 = new Category;
        $category4->title = 'HSD';
        $category4->description = 'Hardware- Software Design';
        $category4->created_at = date("Y-m-d H:i:s");
        $category4->updated_at = date("Y-m-d H:i:s");
        $category4->save();
    }
}
