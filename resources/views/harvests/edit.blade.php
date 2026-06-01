<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Data Panen') }}
            </h2>
            <div class="text-sm text-gray-500">
                ID: #{{ $harvest->id }}
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

            @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4 shadow-sm">
                <div class="font-bold mb-2">⚠️ Ada kesalahan:</div>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                    <h3 class="text-white font-bold text-lg">✏️ Edit Data Panen</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('harvests.update', $harvest) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    🌱 Jenis Tanaman <span class="text-red-500">*</span>
                                </label>
                                <select name="plant_id" required class="shadow border rounded-lg w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                    @foreach($plants as $plant)
                                    <option value="{{ $plant->id }}" {{ $harvest->plant_id == $plant->id ? 'selected' : '' }}>
                                        {{ $plant->name }} @if($plant->default_price) (Rp {{ number_format($plant->default_price, 0, ',', '.') }}/kg) @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('plant_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    📅 Tanggal Panen <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="date" value="{{ old('date', $harvest->date->format('Y-m-d')) }}" required
                                    class="shadow border rounded-lg w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    ⚖️ Berat Panen (kg) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="weight_kg" step="0.1" value="{{ old('weight_kg', $harvest->weight_kg) }}" required
                                    class="shadow border rounded-lg w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                @error('weight_kg') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    💰 Harga per Kg (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="price_per_kg" step="500" value="{{ old('price_per_kg', $harvest->price_per_kg) }}" required
                                    class="shadow border rounded-lg w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                @error('price_per_kg') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Info Ringkasan -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Total Pendapatan:</span>
                                    <span class="font-bold text-green-600 block text-lg">
                                        Rp {{ number_format($harvest->weight_kg * $harvest->price_per_kg, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Dibuat pada:</span>
                                    <span class="font-medium block">{{ $harvest->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Terakhir update:</span>
                                    <span class="font-medium block">{{ $harvest->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-3 px-6 rounded-lg transition transform hover:scale-105 shadow-md">
                                💾 Update Data
                            </button>
                            <a href="{{ route('harvests.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tombol Hapus -->
            <div class="mt-4 text-right">
                <form action="{{ route('harvests.destroy', $harvest) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data panen ini? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm flex items-center gap-1 ml-auto">
                        <span>🗑️</span> Hapus Data Panen
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>