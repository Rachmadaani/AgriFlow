<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Pengeluaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4 flex justify-between items-center">
                        <a href="{{ route('expenses.create') }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            + Tambah Pengeluaran
                        </a>
                        <div class="text-sm text-gray-500">
                            Total: <span class="font-bold">Rp {{ number_format($expenses->sum('amount'), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($expenses as $expense)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $expense->date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 rounded-full text-xs 
                                            @if($expense->category == 'bibit') bg-green-100 text-green-800
                                            @elseif($expense->category == 'pupuk') bg-blue-100 text-blue-800
                                            @elseif($expense->category == 'pestisida') bg-red-100 text-red-800
                                            @elseif($expense->category == 'transport') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($expense->category) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">
                                        Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $expense->note ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('expenses.edit', $expense) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin hapus pengeluaran ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <div class="text-4xl mb-2">💰</div>
                                        Belum ada data pengeluaran<br>
                                        <a href="{{ route('expenses.create') }}" class="text-yellow-600 hover:text-yellow-800 text-sm mt-2 inline-block">+ Tambah pengeluaran pertama</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $expenses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>