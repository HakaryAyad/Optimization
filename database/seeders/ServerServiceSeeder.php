<?php

namespace Database\Seeders;

use App\Models\Server;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServerServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing records to ensure fresh 4 servers and 12 services
        Service::query()->delete();
        Server::query()->delete();

        $servers = [
            ['name' => 'Server Alpha (Primary)', 'cpu' => 32, 'memory' => 64, 'storage' => 500, 'latency' => 12, 'max_services' => 3],
            ['name' => 'Server Beta (Compute)', 'cpu' => 24, 'memory' => 48, 'storage' => 400, 'latency' => 18, 'max_services' => 3],
            ['name' => 'Server Gamma (Edge)', 'cpu' => 16, 'memory' => 32, 'storage' => 250, 'latency' => 8, 'max_services' => 3],
            ['name' => 'Server Delta (Storage)', 'cpu' => 20, 'memory' => 40, 'storage' => 350, 'latency' => 24, 'max_services' => 3],
        ];

        foreach ($servers as $data) {
            Server::create($data);
        }

        $services = [
            ['name' => 'Auth Service', 'cpu' => 4, 'memory' => 8, 'storage' => 40],
            ['name' => 'Payment API', 'cpu' => 6, 'memory' => 12, 'storage' => 50],
            ['name' => 'Data Analytics', 'cpu' => 10, 'memory' => 20, 'storage' => 120],
            ['name' => 'Cache Cluster', 'cpu' => 4, 'memory' => 16, 'storage' => 30],
            ['name' => 'API Gateway', 'cpu' => 8, 'memory' => 10, 'storage' => 35],
            ['name' => 'Search Engine', 'cpu' => 12, 'memory' => 18, 'storage' => 90],
            ['name' => 'Notification Hub', 'cpu' => 3, 'memory' => 6, 'storage' => 20],
            ['name' => 'Media Transcoder', 'cpu' => 14, 'memory' => 14, 'storage' => 100],
            ['name' => 'Log Aggregator', 'cpu' => 5, 'memory' => 8, 'storage' => 80],
            ['name' => 'Inventory Manager', 'cpu' => 4, 'memory' => 8, 'storage' => 45],
            ['name' => 'User Profiles API', 'cpu' => 3, 'memory' => 6, 'storage' => 25],
            ['name' => 'Reporting Daemon', 'cpu' => 7, 'memory' => 10, 'storage' => 65],
        ];

        foreach ($services as $data) {
            Service::create($data);
        }
    }
}
