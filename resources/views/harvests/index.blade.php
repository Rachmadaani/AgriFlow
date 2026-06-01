<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('📋 Data Panen') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('harvests.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-sm text-sm">
                    + Input Panen Baru
                </a>
                <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-sm text-sm">
                    ← Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-4 shadow-sm">
                <div class="flex items-center">
                    <div class="text-lg mr-2">✅</div>
                    <div>{{ session('success') }}</div>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4 shadow-sm">
                <div class="flex items-center">
                    <div class="text-lg mr-2">❌</div>
                    <div>{{ session('error') }}</div>
                </div>
            </div>
            @endif

            <!-- Statistik Ringkasan -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
                    <div class="text-sm text-gray-500">Total Panen</div>
                    <div class="text-2xl font-bold text-gray-800">{{ number_format($harvests->sum('weight_kg'), 1) }} kg</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
                    <div class="text-sm text-gray-500">Total Pendapatan</div>
                    <div class="text-2xl font-bold text-green-600">
                        Rp {{ number_format($harvests->sum(function($h) { return $h->weight_kg * $h->price_per_kg; }), 0, ',', '.') }}
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-purple-500">
                    <div class="text-sm text-gray-500">Rata-rata per Panen</div>
                    <div class="text-2xl font-bold text-gray-800">
                        {{ number_format($harvests->avg('weight_kg') ?? 0, 1) }} kg
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-orange-500">
                    <div class="text-sm text-gray-500">Total Transaksi</div>
                    <div class="text-2xl font-bold text-gray-800">{{ $harvests->total() }}</div>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanaman</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berat (kg)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga/kg</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($harvests as $harvest)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $harvest->date->format('d/m/Y') }}
                                    <div class="text-xs text-gray-400">{{ $harvest->date->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $harvest->plant->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    {{ number_format($harvest->weight_kg, 1) }} kg
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    Rp {{ number_format($harvest->price_per_kg, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                    Rp {{ number_format($harvest->weight_kg * $harvest->price_per_kg, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        <a href="{{ route('harvests.edit', $harvest) }}"
                                            class="text-blue-600 hover:text-blue-900 transition">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('harvests.destroy', $harvest) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 transition"
                                                onclick="return confirm('Yakin hapus data panen ini?')">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="text-6xl mb-3">📭</div>
                                    <p class="text-lg">Belum ada data panen</p>
                                    <a href="{{ route('harvests.create') }}" class="text-green-600 hover:text-green-800 text-sm mt-2 inline-block">
                                        + Input panen pertama
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $harvests->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>