<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Elektroninė muzika',
            'Pop muzika',
            'Garso suvedimas',
            'Garso dizainas',
            'Produkcija',
            'Garsų įrašymas',
            'Deep House',
            'Techno',
            'Tech House',
            'Hip-Hop muzika',
        ];

        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }
    }
}
