<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'pending',
            'processing',
            'unpaid',
            'paid',
            'picking',
            'shipped',
            'delivered',
            'cancelled',
        ];

        foreach ($statuses as $status) {
            OrderStatus::create(['name' => $status]);
        }
    }
}
