<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Article;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Categorías
        $cat1 = Category::create(['name' => 'Tours', 'description' => 'Paquetes turísticos guiados', 'active' => true]);
        $cat2 = Category::create(['name' => 'Experiencias', 'description' => 'Actividades personalizadas', 'active' => true]);
        $cat3 = Category::create(['name' => 'Naturaleza', 'description' => 'Senderismo y aventura', 'active' => true]);

        // Marcas
        $brand1 = Brand::create(['name' => 'Vivra Travel', 'description' => 'Marca principal', 'active' => true]);
        $brand2 = Brand::create(['name' => 'Oaxaca Adventures', 'description' => 'Tours Oaxaca', 'active' => true]);

        // Artículos
        Article::create([
            'name' => 'Tour Oaxaca Premium',
            'description' => 'Tour completo por Oaxaca',
            'price' => 350.00,
            'stock' => 12,
            'category_id' => $cat1->id,
            'brand_id' => $brand1->id,
            'active' => true
        ]);

        Article::create([
            'name' => 'Clase de Cocina Oaxaca',
            'description' => 'Clase de cocina tradicional',
            'price' => 180.00,
            'stock' => 25,
            'category_id' => $cat2->id,
            'brand_id' => $brand1->id,
            'active' => true
        ]);

        Article::create([
            'name' => 'Senderismo en Montaña',
            'description' => 'Caminata en montaña',
            'price' => 120.00,
            'stock' => 8,
            'category_id' => $cat3->id,
            'brand_id' => $brand2->id,
            'active' => true
        ]);
    }
}