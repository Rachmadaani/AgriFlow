<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Input Panen Harian') }}
            </h2>
            <div class="text-sm text-gray-500">
                {{ now()->format('l, d F Y') }}
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

            <!-- Form Input Panen -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-6">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <h3 class="text-white font-bold text-lg">📊 Form Panen Hari Ini</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('harvests.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    🌱 Jenis Tanaman <span class="text-red-500">*</span>
                                </label>
                                <select name="plant_id" required class="shadow border rounded-lg w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Pilih Tanaman</option>
                                    @foreach($plants as $plant)
                                    <option value="{{ $plant->id }}" {{ old('plant_id') == $plant->id ? 'selected' : '' }}>
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
                                <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                                    class="shadow border rounded-lg w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                                @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    ⚖️ Berat Panen (kg) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="weight_kg" step="0.1" placeholder="Contoh: 8.5" value="{{ old('weight_kg') }}" required
                                    class="shadow border rounded-lg w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                                <p class="text-xs text-gray-500 mt-1">Masukkan dalam kilogram (kg)</p>
                                @error('weight_kg') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    💰 Harga per Kg (Rp)
                                </label>
                                <input type="number" name="price_per_kg" step="500" placeholder="Kosongkan pakai harga default" value="{{ old('price_per_kg') }}"
                                    class="shadow border rounded-lg w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                                <p class="text-xs text-gray-500 mt-1">✨ Biarkan kosong untuk menggunakan harga default tanaman</p>
                                @error('price_per_kg') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition transform hover:scale-105 shadow-md">
                                ✅ Simpan Panen
                            </button>
                            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips Cepat -->
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="text-2xl mr-3">💡</div>
                    <div>
                        <h4 class="font-bold text-blue-800">Tips Cepat Input Panen</h4>
                        <p class="text-sm text-blue-700 mt-1">
                            • Isi berat panen dalam kilogram (contoh: 8.5 untuk 8 kg setengah)<br>
                            • Jika harga tidak diisi, sistem akan menggunakan harga default tanaman<br>
                            • Data akan langsung masuk ke grafik dashboard
                        </p>
                    </div>
                </div>
            </div>

            <!-- Link ke daftar panen -->
            <div class="mt-4 text-center">
                <a href="{{ route('harvests.index') }}" class="text-green-600 hover:text-green-800 text-sm">
                    📋 Lihat Semua Data Panen →
                </a>
            </div>
        </div>
    </div>
</x-app-layout>