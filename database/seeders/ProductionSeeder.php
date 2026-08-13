<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Feature;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Production'da sadece gerçek referans verisi seed edilir: kategoriler,
     * Kocaeli il/ilçe/mahalle listesi, özellik etiketleri. Sahte ilan, danışman,
     * fotoğraf veya test kullanıcısı YOK — bunlar admin panelden veya bottan
     * gerçek verilerle doldurulacak.
     */
    public function run(): void
    {
        if (Category::count() === 0) {
            Category::factory(7)->create();
        }

        if (Feature::count() === 0) {
            Feature::factory(10)->create();
        }

        $this->call(LocationSeeder::class);
    }
}
