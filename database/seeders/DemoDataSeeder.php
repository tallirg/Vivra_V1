<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Brand;
use App\Models\Article;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Desactivar llaves foráneas para SQLite
        Schema::disableForeignKeyConstraints();

        // 1. Asegurar usuario base
        $user = User::first() ?? User::create([
            'name' => 'Usuario Demo',
            'email' => 'demo@vivra.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Obtener o crear prestador local
        $brand = Brand::first() ?? Brand::create([
            'name' => 'Colectivo Turístico Oaxaqueño',
            'description' => 'Prestadores locales tradicionales',
            'active' => true,
        ]);

        // 3. Crear las 10 Experiencias
        $exps = [
            ['name' => 'Taller de Mezcal Silvestre en Matatlán', 'description' => 'Aprende el proceso de destilación artesanal con maguey silvestre.', 'price' => 1200, 'stock' => 15, 'category_id' => 7, 'brand_id' => $brand->id, 'active' => true],
            ['name' => 'Recorrido por Telares de Pedales', 'description' => 'Demostración de teñido natural con grana cochinilla en Teotitlán.', 'price' => 850, 'stock' => 10, 'category_id' => 5, 'brand_id' => $brand->id, 'active' => true],
            ['name' => 'Elaboración de Chocolate Artesanal', 'description' => 'Muele tu propio cacao en metate con canela y azúcar.', 'price' => 600, 'stock' => 12, 'category_id' => 4, 'brand_id' => $brand->id, 'active' => true],
            ['name' => 'Caminata por los Pueblos Mancomunados', 'description' => 'Senderismo guiado por la Sierra Norte entre neblina y pino.', 'price' => 950, 'stock' => 8, 'category_id' => 6, 'brand_id' => $brand->id, 'active' => true],
            ['name' => 'Cata Profesional de Mezcales', 'description' => 'Degustación guiada por maestro mezcalero con maridaje oaxaqueño.', 'price' => 1400, 'stock' => 15, 'category_id' => 7, 'brand_id' => $brand->id, 'active' => true],
            ['name' => 'Taller de Cocina: Moles y Tamales', 'description' => 'Prepara mole negro desde cero con insumos del mercado local.', 'price' => 1100, 'stock' => 10, 'category_id' => 4, 'brand_id' => $brand->id, 'active' => true],
            ['name' => 'Taller de Bordado Tradicional Oaxaqueño', 'description' => 'Aprende las puntadas icónicas de los huipiles de San Antonino.', 'price' => 700, 'stock' => 8, 'category_id' => 5, 'brand_id' => $brand->id, 'active' => true],
            ['name' => 'Ruta del Pan de Yema y Chocolate', 'description' => 'Recorrido por panaderías antiguas del centro histórico.', 'price' => 500, 'stock' => 20, 'category_id' => 4, 'brand_id' => $brand->id, 'active' => true],
            ['name' => 'Excursión a Hierve el Agua', 'description' => 'Viaje de un día a las pozas naturales y cascadas petrificadas.', 'price' => 800, 'stock' => 12, 'category_id' => 6, 'brand_id' => $brand->id, 'active' => true],
            ['name' => 'Taller de Tallado de Alebrijes', 'description' => 'Moldea y pinta tu propia figura de madera de copal en Arrazola.', 'price' => 750, 'stock' => 10, 'category_id' => 5, 'brand_id' => $brand->id, 'active' => true],
        ];

        foreach ($exps as $e) {
            Article::create($e);
        }

        // 4. Crear Reservaciones de prueba
        $articles = Article::all();

        if ($articles->count() >= 5) {
            $statuses = ['completed', 'confirmed', 'pending', 'completed', 'confirmed'];
            foreach ($articles->take(5) as $i => $art) {
                Order::create([
                    'user_id' => $user->id,
                    'experience_id' => $art->id,
                    'total_price' => $art->price,
                    'status' => $statuses[$i]
                ]);
            }
        }

        // 5. Crear Reseñas de prueba
        if ($articles->count() >= 3 && class_exists('App\Models\Review')) {
            $reviewsData = [
                ['rating' => 5, 'comment' => 'Una experiencia increíble. El maestro artesano nos explicó todo con mucha paciencia.'],
                ['rating' => 5, 'comment' => 'Deliciosa comida y excelente atención. Súper recomendado para conocer las tradiciones.'],
                ['rating' => 4, 'comment' => 'Muy buen recorrido y excelente paisaje. Vale totalmente la pena llevar ropa cómoda.']
            ];

            foreach ($articles->take(3) as $i => $art) {
                try {
                    Review::create([
                        'user_id' => $user->id,
                        'experience_id' => $art->id,
                        'rating' => $reviewsData[$i]['rating'],
                        'comment' => $reviewsData[$i]['comment']
                    ]);
                } catch (\Exception $ex) {
                    Review::create([
                        'user_id' => $user->id,
                        'article_id' => $art->id,
                        'rating' => $reviewsData[$i]['rating'],
                        'comment' => $reviewsData[$i]['comment']
                    ]);
                }
            }
        }

        // Activar de nuevo llaves foráneas
        Schema::enableForeignKeyConstraints();
    }
}
