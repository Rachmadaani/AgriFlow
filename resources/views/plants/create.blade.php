<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Tanaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('plants.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Tanaman</label>
                            <input type="text" name="name" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('name') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Harga Default per Kg</label>
                            <input type="number" name="default_price" step="500" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                            <p class="text-sm text-gray-500">Opsional: akan otomatis terisi jika tidak diisi saat panen</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Tanam</label>
                            <input type="date" name="planting_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                            <a href="{{ route('plants.index') }}" class="text-gray-600 hover:text-gray-900">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>