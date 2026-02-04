<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('data/notes.json'));
        $notes = json_decode($json, true);

        if (!$notes) {
            return;
        }

        foreach ($notes as $note) {
            Note::firstOrCreate(
                ['name' => $note['name']],
                ['type' => $note['type']]
            );
        }
    }
}
