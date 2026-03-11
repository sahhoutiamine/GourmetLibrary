<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['name' => 'Pâtisserie Française', 'description' => 'L\'art des gâteaux et viennoiseries.']);
        Category::create(['name' => 'Cuisine du Monde', 'description' => 'Voyage culinaire à travers les continents.']);
        Category::create(['name' => 'Sans Gluten', 'description' => 'Recettes savoureuses pour tous.']);
        Category::create(['name' => 'Cuisine Italienne', 'description' => 'La passion des pâtes et du risotto.']);
    }
}
