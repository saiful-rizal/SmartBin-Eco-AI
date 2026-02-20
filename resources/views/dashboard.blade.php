<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBin Eco-AI Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="text-gray-800">

    <!-- Navbar Modern -->
    <nav class="bg-emerald-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-white p-2 rounded-full text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <span class="text-white font-bold text-xl tracking-wide">SmartBin <span class="text-emerald-200">Eco-AI</span></span>
                </div>
                <div>
                    <a href="{{ route('reset') }}" class="text-emerald-100 hover:text-white text-sm font-medium transition">Reset Data</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Notification -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm animate-bounce">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Kolom Kiri: Panel Kontrol Simulasi -->
            <div class="lg:col-span-1 space-y-6">
                <div class="glass-card rounded-2xl shadow-xl p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="bg-blue-100 text-blue-600 p-1 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Simulasi AI Sensor
                    </h2>
                    <p class="text-sm text-gray-500 mb-6">Masukkan data seolah-olah sensor AI mendeteksi sampah yang masuk.</p>
                    
                    <form action="{{ route('add.trash') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Lokasi SmartBin</label>
                            <select name="bin_id" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2.5 transition">
                                @foreach($bins as $bin)
                                    <option value="{{ $bin->id }}">{{ $bin->name }} ({{ $bin->type }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Berat Sampah (Gram)</label>
                            <input type="number" name="weight" placeholder="Contoh: 250" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2.5 transition" required>
                        </div>

                        <button type="submit" class="w-full text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition shadow-md transform hover:-translate-y-0.5">
                            Proses Data Sampah
                        </button>
                    </form>
                </div>

                <!-- Stats Summary -->
                <div class="glass-card rounded-2xl shadow-xl p-6 bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
                    <h3 class="text-lg font-semibold mb-2">Total Impact</h3>
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-sm opacity-80">Poin Terkumpul</p>
                            <p class="text-3xl font-bold">{{ $bins->sum('total_points') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-80">Total Sampah</p>
                            <p class="text-xl font-bold">{{ $bins->sum('current_weight') }}g</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Grid Status SmartBin -->
            <div class="lg:col-span-2">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Status Tong Sampah</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    @forelse($bins as $bin)
                        @php
                            $percentage = ($bin->current_weight / $bin->max_capacity) * 100;
                            $isFull = $percentage >= 90;
                            $color = $isFull ? 'red' : ($percentage > 50 ? 'yellow' : 'emerald');
                        @endphp

                        <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition duration-300 p-6 border-t-4 {{ $isFull ? 'border-red-500' : 'border-emerald-500' }}">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-bold text-lg text-gray-800">{{ $bin->name }}</h3>
                                    <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full mt-1 capitalize">{{ $bin->type }}</span>
                                </div>
                                @if($isFull)
                                    <div class="bg-red-100 text-red-600 p-2 rounded-full animate-pulse">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Kapasitas</span>
                                    <span>{{ number_format($percentage, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-{{ $color }}-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-6 pt-4 border-t border-gray-100">
                                <div class="text-center">
                                    <p class="text-xs text-gray-400">Berat Saat Ini</p>
                                    <p class="font-semibold text-gray-700">{{ $bin->current_weight }} / {{ $bin->max_capacity }} g</p>
                                </div>
                                <div class="text-center border-l border-gray-100">
                                    <p class="text-xs text-gray-400">Poin Lokasi</p>
                                    <p class="font-semibold text-emerald-600">{{ $bin->total_points }} pts</p>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="col-span-2 text-center py-10 bg-white rounded-xl shadow-sm">
                            <p class="text-gray-500">Belum ada data SmartBin. <a href="{{ route('reset') }}" class="text-emerald-600 font-bold hover:underline">Klik Reset untuk inisialisasi awal.</a></p>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </main>

</body>
</html>