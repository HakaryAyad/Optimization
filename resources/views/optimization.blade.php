<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server & Service Performance Optimization</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            mermaid.initialize({ startOnLoad: true, theme: 'neutral' });
        });
    </script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen font-sans antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Top Navigation Header -->
    <header class="border-b border-slate-800 bg-slate-900/80 backdrop-blur sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-emerald-400 flex items-center justify-center font-bold text-slate-950 shadow-lg shadow-emerald-500/20">
                    ⚡
                </div>
                <div>
                    <h1 class="text-lg font-bold text-white tracking-tight">Server Performance Optimizer</h1>
                    <p class="text-xs text-slate-400">Swap-Based Local Search &bull; 4 Servers &bull; 12 Services</p>
                </div>
            </div>

            <!-- Modal Triggers & Actions -->
            <div class="flex items-center gap-2">
                <button type="button" onclick="openModal('serverModal')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-800 text-slate-200 hover:bg-slate-700 hover:text-white border border-slate-700 transition">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                    Servers Modal
                </button>

                <button type="button" onclick="openModal('serviceModal')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-800 text-slate-200 hover:bg-slate-700 hover:text-white border border-slate-700 transition">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Services Modal
                </button>

                <a href="{{ route('optimization.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-500 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Re-Optimize
                </a>

                <form method="POST" action="{{ route('optimization.reset') }}" class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('Reset all servers and services to default initial state?')" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-400 hover:text-rose-300 hover:bg-rose-950/40 border border-transparent hover:border-rose-900 transition" title="Reset defaults">
                        Reset
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- Flash Message -->
        @if (session('status'))
            <div class="rounded-xl border border-emerald-500/30 bg-emerald-950/40 p-4 text-emerald-200 text-sm flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-2">
                    <span class="text-emerald-400 text-base">✓</span>
                    <span>{{ session('status') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white">&times;</button>
            </div>
        @endif

        <!-- Performance Summary Cards -->
        <section>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Final Performance Score -->
                <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between text-slate-400 text-xs font-medium uppercase tracking-wider">
                        <span>Optimized Performance</span>
                        <span class="text-emerald-400 text-base">⭐</span>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl font-black text-emerald-400 tracking-tight">{{ $finalScore }}%</span>
                        <span class="text-xs font-bold text-emerald-500 bg-emerald-950/80 border border-emerald-800/80 px-2 py-0.5 rounded-full">+{{ $scoreGain }}%</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Up from {{ $initialScore }}% initial placement</p>
                </div>

                <!-- Swaps Performed -->
                <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between text-slate-400 text-xs font-medium uppercase tracking-wider">
                        <span>Services Swapped</span>
                        <span class="text-cyan-400 text-base">🔄</span>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl font-black text-white tracking-tight">{{ $swaps }}</span>
                        <span class="text-xs font-medium text-slate-400">successful swaps</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">{{ $evaluations }} swap pairs evaluated</p>
                </div>

                <!-- Execution Time -->
                <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between text-slate-400 text-xs font-medium uppercase tracking-wider">
                        <span>Convergence Time</span>
                        <span class="text-amber-400 text-base">⏱️</span>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl font-black text-white tracking-tight">{{ $executionTimeMs }}</span>
                        <span class="text-sm font-semibold text-amber-400">ms</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Fast local search swap optimization</p>
                </div>

                <!-- Allocation Rule -->
                <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between text-slate-400 text-xs font-medium uppercase tracking-wider">
                        <span>Capacity Division</span>
                        <span class="text-indigo-400 text-base">📦</span>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl font-black text-white tracking-tight">12 / 12</span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-indigo-950 text-indigo-400 border border-indigo-800">Balanced</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Exactly 3 services per server</p>
                </div>
            </div>
        </section>

        <!-- ======================================================== -->
        <!-- DIAGRAM 1: PERFORMANCE DIAGRAM                           -->
        <!-- ======================================================== -->
        <section class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-md space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-white tracking-tight flex items-center gap-2">
                        <span>📊</span> Server Performance Diagram
                    </h2>
                    <p class="text-xs text-slate-400">Compares individual server performance scores, latency, and resource workloads after swap optimization.</p>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="px-2.5 py-1 rounded bg-slate-800 text-slate-300 border border-slate-700">Cluster Avg: <strong class="text-emerald-400">{{ $finalScore }}%</strong></span>
                </div>
            </div>

            <!-- Performance Bar Chart Diagram -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach ($serverMetrics as $metric)
                    @php
                        $perf = $metric['performanceScore'];
                        $colorClass = $perf >= 85 ? 'text-emerald-400 bg-emerald-500' : ($perf >= 70 ? 'text-cyan-400 bg-cyan-500' : 'text-amber-400 bg-amber-500');
                    @endphp
                    <div class="bg-slate-950 border border-slate-800/90 rounded-xl p-4 flex flex-col justify-between space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-white truncate">{{ $metric['server']->name }}</span>
                                <span class="text-xs font-bold font-mono px-2 py-0.5 rounded bg-slate-900 border border-slate-800 {{ explode(' ', $colorClass)[0] }}">
                                    {{ $perf }}%
                                </span>
                            </div>

                            <!-- Performance Gauge Bar -->
                            <div class="w-full bg-slate-800 rounded-full h-3 overflow-hidden p-0.5">
                                <div class="{{ explode(' ', $colorClass)[1] }} h-2 rounded-full transition-all duration-500" style="width: {{ $perf }}%"></div>
                            </div>
                        </div>

                        <!-- Performance Factors Breakdown -->
                        <div class="space-y-1.5 text-[11px] font-mono border-t border-slate-900 pt-3">
                            <div class="flex justify-between text-slate-400">
                                <span>Latency:</span>
                                <span class="text-amber-400 font-bold">{{ $metric['server']->latency }} ms</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>CPU Load:</span>
                                <span class="text-cyan-400 font-bold">{{ $metric['cpuPercent'] }}%</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>RAM Load:</span>
                                <span class="text-indigo-400 font-bold">{{ $metric['memoryPercent'] }}%</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Storage Load:</span>
                                <span class="text-emerald-400 font-bold">{{ $metric['storagePercent'] }}%</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Swap Improvement Progression -->
            @if (count($history) > 1)
                <div class="bg-slate-950/70 border border-slate-800/80 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-slate-300">Swap Optimization Trajectory</span>
                        <span class="text-[11px] text-slate-400">{{ count($history) - 1 }} improvement steps</span>
                    </div>
                    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-mono">
                        @foreach ($history as $idx => $stepScore)
                            <div class="flex items-center gap-2 shrink-0">
                                <div class="px-2.5 py-1 rounded-lg {{ $loop->last ? 'bg-emerald-950 text-emerald-300 border border-emerald-700 font-bold' : ($loop->first ? 'bg-slate-900 text-slate-400 border border-slate-800' : 'bg-slate-800/80 text-cyan-300 border border-slate-700') }}">
                                    @if ($loop->first)
                                        Start: {{ $stepScore }}%
                                    @elseif ($loop->last)
                                        Peak: {{ $stepScore }}%
                                    @else
                                        #{{ $idx }}: {{ $stepScore }}%
                                    @endif
                                </div>
                                @if (!$loop->last)
                                    <span class="text-slate-600 font-bold">&rarr;</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>

        <!-- ======================================================== -->
        <!-- DIAGRAM 2: 4 SERVERS CARDS & SERVICES DOCK               -->
        <!-- ======================================================== -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white tracking-tight flex items-center gap-2">
                        <span>🖥️</span> 4 Servers Allocation (3 Services per Server)
                    </h2>
                    <p class="text-sm text-slate-400">The 12 services docked into their optimal servers.</p>
                </div>
            </div>

            <!-- 4 Servers Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($serverMetrics as $metric)
                    @php
                        $server = $metric['server'];
                        $servicesAssigned = $metric['services'];
                    @endphp
                    <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 flex flex-col justify-between shadow-md transition space-y-5">
                        
                        <!-- Server Card Header -->
                        <div class="border-b border-slate-800/80 pb-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-400 bg-indigo-950/70 border border-indigo-800/60 px-2 py-0.5 rounded">
                                        Server #{{ $server->id }}
                                    </span>
                                    <h3 class="text-base font-bold text-white mt-1">{{ $server->name }}</h3>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-semibold bg-amber-950/80 text-amber-300 border border-amber-800/60">
                                    ⚡ {{ $server->latency }} ms
                                </span>
                            </div>

                            <!-- Resource Meters -->
                            <div class="mt-4 space-y-2 text-xs">
                                <div>
                                    <div class="flex justify-between text-slate-300 font-mono mb-1">
                                        <span class="text-slate-400">CPU ({{ $metric['usedCpu'] }}/{{ $server->cpu }}c)</span>
                                        <span class="font-bold text-cyan-400">{{ $metric['cpuPercent'] }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-cyan-500 h-1.5 rounded-full" style="width: {{ $metric['cpuPercent'] }}%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-slate-300 font-mono mb-1">
                                        <span class="text-slate-400">RAM ({{ $metric['usedMemory'] }}/{{ $server->memory }}GB)</span>
                                        <span class="font-bold text-indigo-400">{{ $metric['memoryPercent'] }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $metric['memoryPercent'] }}%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-slate-300 font-mono mb-1">
                                        <span class="text-slate-400">Disk ({{ $metric['usedStorage'] }}/{{ $server->storage }}GB)</span>
                                        <span class="font-bold text-emerald-400">{{ $metric['storagePercent'] }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $metric['storagePercent'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3 Docked Services -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-xs text-slate-400 font-medium">
                                <span>Services (3/3 Capacity)</span>
                                <span class="text-[11px] font-bold text-emerald-400">Score: {{ $metric['performanceScore'] }}%</span>
                            </div>

                            <div class="space-y-2">
                                @foreach ($servicesAssigned as $svc)
                                    <div class="bg-slate-950/70 border border-slate-800 rounded-xl p-2.5 text-xs">
                                        <div class="font-medium text-slate-200 flex items-center justify-between">
                                            <span>{{ $svc->name }}</span>
                                            <span class="text-[10px] text-slate-500 font-mono">#{{ $svc->id }}</span>
                                        </div>
                                        <div class="mt-2 flex items-center gap-1.5 text-[10px] font-mono">
                                            <span class="px-1.5 py-0.5 rounded bg-cyan-950/80 text-cyan-300 border border-cyan-900/60 font-semibold">{{ $svc->cpu }}c</span>
                                            <span class="px-1.5 py-0.5 rounded bg-indigo-950/80 text-indigo-300 border border-indigo-900/60 font-semibold">{{ $svc->memory }}G</span>
                                            <span class="px-1.5 py-0.5 rounded bg-emerald-950/80 text-emerald-300 border border-emerald-900/60 font-semibold">{{ $svc->storage }}G</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </section>

        <!-- DIAGRAM 3: Mermaid Flow Diagram -->
        <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
            <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                <span>🌐</span> Architecture & Assignment Flowchart
            </h2>

            <div class="bg-slate-950 rounded-xl p-4 overflow-x-auto flex justify-center">
                <div class="mermaid text-center">
graph TD
    subgraph S1 ["{{ $servers[0]->name }} | Perf: {{ $serverMetrics[0]['performanceScore'] }}%"]
        @foreach($serverMetrics[0]['services'] as $s)
        SVC_{{ $s->id }}["{{ $s->name }} ({{ $s->cpu }}c, {{ $s->memory }}G, {{ $s->storage }}G)"]
        @endforeach
    end

    subgraph S2 ["{{ $servers[1]->name }} | Perf: {{ $serverMetrics[1]['performanceScore'] }}%"]
        @foreach($serverMetrics[1]['services'] as $s)
        SVC_{{ $s->id }}["{{ $s->name }} ({{ $s->cpu }}c, {{ $s->memory }}G, {{ $s->storage }}G)"]
        @endforeach
    end

    subgraph S3 ["{{ $servers[2]->name }} | Perf: {{ $serverMetrics[2]['performanceScore'] }}%"]
        @foreach($serverMetrics[2]['services'] as $s)
        SVC_{{ $s->id }}["{{ $s->name }} ({{ $s->cpu }}c, {{ $s->memory }}G, {{ $s->storage }}G)"]
        @endforeach
    end

    subgraph S4 ["{{ $servers[3]->name }} | Perf: {{ $serverMetrics[3]['performanceScore'] }}%"]
        @foreach($serverMetrics[3]['services'] as $s)
        SVC_{{ $s->id }}["{{ $s->name }} ({{ $s->cpu }}c, {{ $s->memory }}G, {{ $s->storage }}G)"]
        @endforeach
    end

    style S1 fill:#1e1b4b,stroke:#6366f1,stroke-width:2px,color:#fff
    style S2 fill:#0f172a,stroke:#38bdf8,stroke-width:2px,color:#fff
    style S3 fill:#064e3b,stroke:#34d399,stroke-width:2px,color:#fff
    style S4 fill:#451a03,stroke:#fbbf24,stroke-width:2px,color:#fff
                </div>
            </div>
        </section>

    </main>

    <!-- ========================================== -->
    <!-- UI MODAL 1: SERVERS MODAL                  -->
    <!-- ========================================== -->
    <div id="serverModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col shadow-2xl">
            <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span>🖥️</span> Servers Modal
                    </h3>
                    <p class="text-xs text-slate-400">Configure specs (CPU, Memory, Storage, Latency) for each server.</p>
                </div>
                <button type="button" onclick="closeModal('serverModal')" class="text-slate-400 hover:text-white p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto space-y-4">
                @foreach ($servers as $s)
                    <form method="POST" action="{{ route('servers.update', $s) }}" class="bg-slate-950 border border-slate-800 rounded-xl p-4 flex flex-col lg:flex-row items-center gap-3 justify-between">
                        @csrf
                        @method('PUT')
                        <div class="w-full lg:w-1/4">
                            <label class="block text-[11px] font-medium text-slate-400 mb-1">Server Name</label>
                            <input type="text" name="name" value="{{ $s->name }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-indigo-500 font-medium">
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 w-full lg:w-3/5">
                            <div>
                                <label class="block text-[11px] font-medium text-slate-400 mb-1">CPU (Cores)</label>
                                <input type="number" name="cpu" min="1" value="{{ $s->cpu }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1.5 text-xs text-cyan-300 font-mono">
                            </div>
                            <div>
                                <label class="block text-[11px] font-medium text-slate-400 mb-1">RAM (GB)</label>
                                <input type="number" name="memory" min="1" value="{{ $s->memory }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1.5 text-xs text-indigo-300 font-mono">
                            </div>
                            <div>
                                <label class="block text-[11px] font-medium text-slate-400 mb-1">Storage (GB)</label>
                                <input type="number" name="storage" min="1" value="{{ $s->storage }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1.5 text-xs text-emerald-300 font-mono">
                            </div>
                            <div>
                                <label class="block text-[11px] font-medium text-slate-400 mb-1">Latency (ms)</label>
                                <input type="number" name="latency" min="1" value="{{ $s->latency }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1.5 text-xs text-amber-300 font-mono">
                            </div>
                        </div>

                        <div class="w-full lg:w-auto flex justify-end">
                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white transition whitespace-nowrap">
                                Save
                            </button>
                        </div>
                    </form>
                @endforeach
            </div>

            <div class="px-6 py-3 border-t border-slate-800 bg-slate-950/50 flex justify-end">
                <button type="button" onclick="closeModal('serverModal')" class="px-4 py-1.5 rounded-lg text-xs font-medium bg-slate-800 text-slate-300 hover:text-white transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- UI MODAL 2: SERVICES MODAL                 -->
    <!-- ========================================== -->
    <div id="serviceModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col shadow-2xl">
            <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span>⚙️</span> Services Modal (12 Services)
                    </h3>
                    <p class="text-xs text-slate-400">View and adjust resource requirements for each service.</p>
                </div>
                <button type="button" onclick="closeModal('serviceModal')" class="text-slate-400 hover:text-white p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach ($services as $svc)
                        <form method="POST" action="{{ route('services.update', $svc) }}" class="bg-slate-950 border border-slate-800 rounded-xl p-3.5 space-y-2.5">
                            @csrf
                            @method('PUT')
                            <div class="flex items-center justify-between gap-2">
                                <input type="text" name="name" value="{{ $svc->name }}" class="bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-xs text-white font-medium w-full focus:outline-none">
                                <span class="text-[10px] font-mono text-slate-400 whitespace-nowrap bg-slate-900 border border-slate-800 px-1.5 py-0.5 rounded">
                                    {{ $svc->server ? $svc->server->name : 'Unassigned' }}
                                </span>
                            </div>

                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="block text-[10px] text-slate-400">CPU</label>
                                    <input type="number" name="cpu" min="1" value="{{ $svc->cpu }}" class="w-full bg-slate-900 border border-slate-700 rounded px-1.5 py-1 text-xs text-cyan-300 font-mono">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-slate-400">RAM (GB)</label>
                                    <input type="number" name="memory" min="1" value="{{ $svc->memory }}" class="w-full bg-slate-900 border border-slate-700 rounded px-1.5 py-1 text-xs text-indigo-300 font-mono">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-slate-400">Storage (GB)</label>
                                    <input type="number" name="storage" min="1" value="{{ $svc->storage }}" class="w-full bg-slate-900 border border-slate-700 rounded px-1.5 py-1 text-xs text-emerald-300 font-mono">
                                </div>
                            </div>

                            <div class="flex justify-end pt-1">
                                <button type="submit" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-indigo-600 text-[11px] font-semibold text-slate-300 hover:text-white transition">
                                    Update
                                </button>
                            </div>
                        </form>
                    @endforeach
                </div>
            </div>

            <div class="px-6 py-3 border-t border-slate-800 bg-slate-950/50 flex justify-end">
                <button type="button" onclick="closeModal('serviceModal')" class="px-4 py-1.5 rounded-lg text-xs font-medium bg-slate-800 text-slate-300 hover:text-white transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Modals Script -->
    <script>
        function openModal(id) {
            const m = document.getElementById(id);
            if (m) m.classList.remove('hidden');
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            if (m) m.classList.add('hidden');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal('serverModal');
                closeModal('serviceModal');
            }
        });

        document.querySelectorAll('#serverModal, #serviceModal').forEach(function(m) {
            m.addEventListener('click', function(e) {
                if (e.target === m) closeModal(m.id);
            });
        });
    </script>
</body>
</html>
