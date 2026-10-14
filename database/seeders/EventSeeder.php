<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            [
                'title' => 'Concert Event',
                'category' => 'Musik',
                'ticket_price' => 50000,
                'description' => 'A great concert event',
                'image' => 'null',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Festival Event',
                'category' => 'Festival',
                'ticket_price' => 100000,
                'description' => 'A great festival event',
                'image' => 'null',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                
                'title' => 'Seminar Event',
                'category' => 'Seminar',
                'ticket_price' => 25000,
                'description' => 'A great seminar event',
                'image' => 'null',
                'created_at' => now(),
                'updated_at' => now(),
            
            ],
        ]);
    }
}
