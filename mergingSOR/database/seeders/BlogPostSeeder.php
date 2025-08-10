<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\BlogPost;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        BlogPost::truncate();
        BlogPost::insert([
            [
                'title' => 'Pierwszy post na blogu',
                'slug' => Str::slug('Pierwszy post na blogu'),
                'content' => '<p>To jest przykładowa treść pierwszego posta na blogu.</p>',
                'status' => 'active',
                'published_at' => Carbon::now()->subDays(2),
                'is_featured' => true,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'title' => 'Drugi post na blogu',
                'slug' => Str::slug('Drugi post na blogu'),
                'content' => '<p>Drugi post na blogu z przykładową treścią.</p>',
                'status' => 'active',
                'published_at' => Carbon::now()->subDay(),
                'is_featured' => false,
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now()->subDay(),
            ],
            [
                'title' => 'Trzeci post na blogu',
                'slug' => Str::slug('Trzeci post na blogu'),
                'content' => '<p>Trzeci post na blogu. Więcej treści tutaj.</p>',
                'status' => 'active',
                'published_at' => Carbon::now(),
                'is_featured' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
