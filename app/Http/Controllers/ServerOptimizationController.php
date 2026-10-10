<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\Service;
use Database\Seeders\ServerServiceSeeder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServerOptimizationController extends Controller
{
    /**
     * Display the optimization dashboard with swap-based allocation and performance diagram.
     */
    public function index(): View
    {
        if (Server::count() !== 4 || Service::count() !== 12) {
            (new ServerServiceSeeder)->run();
        }

        $servers = Server::orderBy('id')->get();
        $services = Service::orderBy('id')->get();

        // 1. Initial division: Divide 12 services evenly across 4 servers (3 each)
        $assignment = [0 => [0, 1, 2], 1 => [3, 4, 5], 2 => [6, 7, 8], 3 => [9, 10, 11]];
        $initialScore = $this->calculateTotalScore($assignment, $servers, $services);

        // 2. Swap Optimization: swap lower-performing services to improve total performance
        $startTime = microtime(true);
        $swaps = 0;
        $evaluations = 0;
        $history = [round($initialScore, 1)];
        $currentScore = $initialScore;
        $improved = true;

        while ($improved && $swaps < 100) {
            $improved = false;

            // Try swapping service p1 on server s1 with service p2 on server s2
            for ($s1 = 0; $s1 < 4; $s1++) {
                for ($s2 = $s1 + 1; $s2 < 4; $s2++) {
                    for ($p1 = 0; $p1 < 3; $p1++) {
                        for ($p2 = 0; $p2 < 3; $p2++) {
                            $evaluations++;
                            $test = $assignment;
                            $temp = $test[$s1][$p1];
                            $test[$s1][$p1] = $test[$s2][$p2];
                            $test[$s2][$p2] = $temp;

                            $newScore = $this->calculateTotalScore($test, $servers, $services);

                            // If swap improves performance, accept it and continue swapping
                            if ($newScore > $currentScore + 0.1) {
                                $assignment = $test;
                                $currentScore = $newScore;
                                $swaps++;
                                $history[] = round($currentScore, 1);
                                $improved = true;
                                break 4;
                            }
                        }
                    }
                }
            }
        }

        $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);

        // 3. Save optimal assignments to database and compute server performance metrics
        $serverMetrics = [];
        foreach ($assignment as $sIdx => $svcIndices) {
            $server = $servers[$sIdx];
            $assigned = collect($svcIndices)->map(fn ($i) => $services[$i]);

            foreach ($assigned as $svc) {
                if ($svc->server_id !== $server->id) {
                    $svc->update(['server_id' => $server->id]);
                }
            }

            $usedCpu = $assigned->sum('cpu');
            $usedMemory = $assigned->sum('memory');
            $usedStorage = $assigned->sum('storage');

            $serverMetrics[] = [
                'server' => $server,
                'services' => $assigned,
                'usedCpu' => $usedCpu,
                'usedMemory' => $usedMemory,
                'usedStorage' => $usedStorage,
                'cpuPercent' => $server->cpu > 0 ? min(100, round(($usedCpu / $server->cpu) * 100, 1)) : 0,
                'memoryPercent' => $server->memory > 0 ? min(100, round(($usedMemory / $server->memory) * 100, 1)) : 0,
                'storagePercent' => $server->storage > 0 ? min(100, round(($usedStorage / $server->storage) * 100, 1)) : 0,
                'performanceScore' => $this->calculateServerScore($server, $assigned),
            ];
        }

        return view('optimization', [
            'servers' => $servers,
            'services' => $services,
            'serverMetrics' => $serverMetrics,
            'initialScore' => round($initialScore, 1),
            'finalScore' => round($currentScore, 1),
            'scoreGain' => round($currentScore - $initialScore, 1),
            'swaps' => $swaps,
            'evaluations' => $evaluations,
            'history' => $history,
            'executionTimeMs' => $executionTimeMs,
            'totalCapacity' => [
                'cpu' => $servers->sum('cpu'),
                'memory' => $servers->sum('memory'),
                'storage' => $servers->sum('storage'),
            ],
            'totalRequired' => [
                'cpu' => $services->sum('cpu'),
                'memory' => $services->sum('memory'),
                'storage' => $services->sum('storage'),
            ],
        ]);
    }

    /**
     * Calculate performance score (0 - 100) for a single server with its assigned services.
     */
    private function calculateServerScore(Server $server, $assignedServices): float
    {
        $cpu = $assignedServices->sum('cpu');
        $mem = $assignedServices->sum('memory');
        $sto = $assignedServices->sum('storage');

        // Penalty if services exceed server capacity
        $overload = max(0, $cpu - $server->cpu) + max(0, $mem - $server->memory) + max(0, $sto - $server->storage);
        $penalty = $overload * 20;

        // Resource balance and latency efficiency
        $utilization = (($cpu / max(1, $server->cpu)) + ($mem / max(1, $server->memory)) + ($sto / max(1, $server->storage))) / 3;
        $score = 100 - $penalty - ($server->latency * 0.5) - (abs($utilization - 0.7) * 25);

        return round(max(0, min(100, $score)), 1);
    }

    /**
     * Calculate overall cluster performance score across all 4 servers.
     */
    private function calculateTotalScore(array $assignment, $servers, $services): float
    {
        $total = 0;
        foreach ($assignment as $sIdx => $svcIndices) {
            $assigned = collect($svcIndices)->map(fn ($idx) => $services[$idx]);
            $total += $this->calculateServerScore($servers[$sIdx], $assigned);
        }

        return round($total / count($servers), 1);
    }

    /**
     * Reset servers and services back to default values.
     */
    public function reset(): RedirectResponse
    {
        (new ServerServiceSeeder)->run();

        return redirect()->route('optimization.index')->with('status', 'Reset to initial 4 servers and 12 services.');
    }

    /**
     * Update server specifications from the modal.
     */
    public function updateServer(Request $request, Server $server): RedirectResponse
    {
        $server->update($request->validate([
            'name' => ['required', 'string', 'max:100'],
            'cpu' => ['required', 'integer', 'min:1'],
            'memory' => ['required', 'integer', 'min:1'],
            'storage' => ['required', 'integer', 'min:1'],
            'latency' => ['required', 'integer', 'min:1'],
        ]));

        return redirect()->route('optimization.index')->with('status', "Server '{$server->name}' updated successfully.");
    }

    /**
     * Update service requirements from the modal.
     */
    public function updateService(Request $request, Service $service): RedirectResponse
    {
        $service->update($request->validate([
            'name' => ['required', 'string', 'max:100'],
            'cpu' => ['required', 'integer', 'min:1'],
            'memory' => ['required', 'integer', 'min:1'],
            'storage' => ['required', 'integer', 'min:1'],
        ]));

        return redirect()->route('optimization.index')->with('status', "Service '{$service->name}' updated successfully.");
    }
}
