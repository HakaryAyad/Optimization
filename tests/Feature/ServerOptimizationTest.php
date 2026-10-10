<?php

use App\Models\Server;
use App\Models\Service;

test('it displays the optimization dashboard and solves the 4-server 12-service distribution', function () {
    $response = $this->get(route('optimization.index'));

    $response->assertOk();
    $response->assertSee('Server Performance Optimizer');
    $response->assertSee('Optimized Performance');
    $response->assertSee('Server Performance Diagram');
    $response->assertSee('4 Servers Allocation');

    expect(Server::count())->toBe(4);
    expect(Service::count())->toBe(12);

    // Each server must have exactly 3 services assigned
    $servers = Server::with('services')->get();
    foreach ($servers as $server) {
        expect($server->services)->toHaveCount(3);
    }
});

test('it allows updating server specs through the server modal endpoint', function () {
    $this->get(route('optimization.index'));

    $server = Server::first();
    $response = $this->put(route('servers.update', $server), [
        'name' => 'Server Alpha Prime',
        'cpu' => 64,
        'memory' => 128,
        'storage' => 1000,
        'latency' => 5,
    ]);

    $response->assertRedirect(route('optimization.index'));
    $server->refresh();

    expect($server->name)->toBe('Server Alpha Prime');
    expect($server->cpu)->toBe(64);
    expect($server->memory)->toBe(128);
    expect($server->storage)->toBe(1000);
    expect($server->latency)->toBe(5);
});

test('it allows updating service requirements through the service modal endpoint', function () {
    $this->get(route('optimization.index'));

    $service = Service::first();
    $response = $this->put(route('services.update', $service), [
        'name' => 'Ultra Fast Auth Service',
        'cpu' => 8,
        'memory' => 16,
        'storage' => 60,
    ]);

    $response->assertRedirect(route('optimization.index'));
    $service->refresh();

    expect($service->name)->toBe('Ultra Fast Auth Service');
    expect($service->cpu)->toBe(8);
    expect($service->memory)->toBe(16);
    expect($service->storage)->toBe(60);
});

test('it resets servers and services back to default seed data', function () {
    $this->get(route('optimization.index'));

    // Mutate a server name
    $server = Server::first();
    $server->update(['name' => 'Custom Name']);

    $response = $this->post(route('optimization.reset'));
    $response->assertRedirect(route('optimization.index'));

    expect(Server::count())->toBe(4);
    expect(Service::count())->toBe(12);
    expect(Server::where('name', 'Server Alpha (Primary)')->exists())->toBeTrue();
});
