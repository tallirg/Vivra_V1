<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Article;
use App\Models\User;
use App\Models\Experience;
use App\Models\Order;
use App\Models\Review;

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
        $art1 = Article::create([
            'name' => 'Tour Oaxaca Premium',
            'description' => 'Tour completo por Oaxaca',
            'price' => 350.00,
            'stock' => 12,
            'category_id' => $cat1->id,
            'brand_id' => $brand1->id,
            'active' => true
        ]);

        $art2 = Article::create([
            'name' => 'Clase de Cocina Oaxaca',
            'description' => 'Clase de cocina tradicional',
            'price' => 180.00,
            'stock' => 25,
            'category_id' => $cat2->id,
            'brand_id' => $brand1->id,
            'active' => true
        ]);

        $art3 = Article::create([
            'name' => 'Senderismo en Montaña',
            'description' => 'Caminata en montaña',
            'price' => 120.00,
            'stock' => 8,
            'category_id' => $cat3->id,
            'brand_id' => $brand2->id,
            'active' => true
        ]);

        // Usuarios
        $user1 = User::create([
            'name' => 'Juan García',
            'email' => 'juan@example.com',
            'password' => bcrypt('password123'),
            'role' => 'tourist'
        ]);

        $user2 = User::create([
            'name' => 'María López',
            'email' => 'maria@example.com',
            'password' => bcrypt('password123'),
            'role' => 'provider'
        ]);

        // Experiencias
        $exp1 = Experience::create([
            'title' => 'Tour Oaxaca Premium',
            'description' => 'Tour completo por Oaxaca',
            'price' => 350.00,
            'location' => 'Oaxaca',
            'user_id' => $user2->id,
            'capacity' => 10,
            'duration_hours' => 8,
            'rating' => 4.5,
            'active' => true
        ]);

        // Órdenes
        Order::create([
            'user_id' => $user1->id,
            'experience_id' => $exp1->id,
            'quantity' => 2,
            'total_price' => 700.00,
            'status' => 'completed',
            'payment_method' => 'credit_card',
            'order_date' => now()
        ]);

        Order::create([
            'user_id' => $user1->id,
            'experience_id' => $exp1->id,
            'quantity' => 1,
            'total_price' => 180.00,
            'status' => 'confirmed',
            'payment_method' => 'paypal',
            'order_date' => now()
        ]);

        Order::create([
            'user_id' => $user2->id,
            'experience_id' => $exp1->id,
            'quantity' => 3,
            'total_price' => 360.00,
            'status' => 'pending',
            'payment_method' => 'credit_card',
            'order_date' => now()
        ]);

        // Reseñas
        Review::create([
            'user_id' => $user1->id,
            'experience_id' => $exp1->id,
            'rating' => 5,
            'comment' => 'Excelente tour, muy recomendado!',
            'approved' => true
        ]);

        Review::create([
            'user_id' => $user2->id,
            'experience_id' => $exp1->id,
            'rating' => 4,
            'comment' => 'Buena experiencia pero un poco largo',
            'approved' => false
        ]);
    }
}