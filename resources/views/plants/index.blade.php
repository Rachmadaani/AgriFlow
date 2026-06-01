<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Tanaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <a href="{{ route('plants.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Tanaman
                        </a>
                    </div>

                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th>Nama Tanaman</th>
                                <th>Harga Default/kg</th>
                                <th>Tgl Tanam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plants as $plant)
                            <tr>
                                <td>{{ $plant->name }}</td>
                                <td>Rp {{ number_format($plant->default_price, 0, ',', '.') }}</td>
                                <td>{{ $plant->planting_date ? $plant->planting_date->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('plants.edit', $plant) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                    <form action="{{ route('plants.destroy', $plant) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data tanaman</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>