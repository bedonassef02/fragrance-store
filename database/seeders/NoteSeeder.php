<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    public function run(): void
    {
        $notes = [
            // Citrus
            ['name' => 'Bergamot', 'type' => 'top'],
            ['name' => 'Lemon', 'type' => 'top'],
            ['name' => 'Mandarin', 'type' => 'top'],
            ['name' => 'Grapefruit', 'type' => 'top'],
            
            // Floral
            ['name' => 'Rose', 'type' => 'heart'],
            ['name' => 'Jasmine', 'type' => 'heart'],
            ['name' => 'Lavender', 'type' => 'heart'],
            ['name' => 'Neroli', 'type' => 'heart'],
            ['name' => 'Ylang-Ylang', 'type' => 'heart'],
            
            // Spices
            ['name' => 'Black Pepper', 'type' => 'top'],
            ['name' => 'Cardamom', 'type' => 'heart'],
            ['name' => 'Cinnamon', 'type' => 'heart'],
            ['name' => 'Saffron', 'type' => 'heart'],
            
            // Woody & Earthy
            ['name' => 'Sandalwood', 'type' => 'base'],
            ['name' => 'Cedarwood', 'type' => 'base'],
            ['name' => 'Patchouli', 'type' => 'base'],
            ['name' => 'Vetiver', 'type' => 'base'],
            ['name' => 'Oud', 'type' => 'base'],
            
            // Gourmand & Resins
            ['name' => 'Vanilla', 'type' => 'base'],
            ['name' => 'Tonka Bean', 'type' => 'base'],
            ['name' => 'Amber', 'type' => 'base'],
            ['name' => 'Musk', 'type' => 'base'],
            ['name' => 'Tobacco', 'type' => 'base'],
            ['name' => 'Leather', 'type' => 'base'],
        ];

        foreach ($notes as $note) {
            Note::create($note);
        }
    }
}
