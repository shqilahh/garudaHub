<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Password "password" otomatis di-hash oleh Laravel (cast 'hashed' di User)
        $client = User::create([
            'name' => 'Budi', 'email' => 'budi@example.com',
            'password' => 'password', 'role' => 'client',
        ]);
        $dev1 = User::create([
            'name' => 'Dewi', 'email' => 'dewi@example.com',
            'password' => 'password', 'role' => 'developer',
        ]);
        $dev2 = User::create([
            'name' => 'Eko', 'email' => 'eko@example.com',
            'password' => 'password', 'role' => 'developer',
        ]);

        $project = $client->projects()->create([
            'title' => 'Website Toko Online',
            'description' => 'Butuh toko online dengan fitur keranjang belanja.',
            'category' => 'Web',
            'budget' => 5000000,
            'bid_deadline' => now()->addDays(7),
        ]);

        $project->bids()->create([
            'developer_id' => $dev1->id, 'price' => 4500000,
            'duration_days' => 14, 'proposal' => 'Saya pakai Laravel dan MySQL.',
        ]);
        $project->bids()->create([
            'developer_id' => $dev2->id, 'price' => 4800000,
            'duration_days' => 10, 'proposal' => 'Pengalaman 3 tahun di web.',
        ]);
    }
}
