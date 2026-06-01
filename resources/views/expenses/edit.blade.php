<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengeluaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('expenses.update', $expense) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                                <input type="date" name="date" value="{{ $expense->date->format('Y-m-d') }}" required class="shadow border rounded w-full py-2 px-3">
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                                <select name="category" required class="shadow border rounded w-full py-2 px-3">
                                    <option value="bibit" {{ $expense->category == 'bibit' ? 'selected' : '' }}>🌱 Bibit</option>
                                    <option value="pupuk" {{ $expense->category == 'pupuk' ? 'selected' : '' }}>💊 Pupuk</option>
                                    <option value="pestisida" {{ $expense->category == 'pestisida' ? 'selected' : '' }}>🐛 Pestisida</option>
                                    <option value="transport" {{ $expense->category == 'transport' ? 'selected' : '' }}>🚚 Transportasi</option>
                                    <option value="tenaga kerja" {{ $expense->category == 'tenaga kerja' ? 'selected' : '' }}>👨‍🌾 Tenaga Kerja</option>
                                    <option value="alat" {{ $expense->category == 'alat' ? 'selected' : '' }}>🔧 Alat</option>
                                    <option value="lainnya" {{ $expense->category == 'lainnya' ? 'selected' : '' }}>📦 Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah (Rp)</label>
                                <input type="number" name="amount" step="1000" value="{{ $expense->amount }}" required class="shadow border rounded w-full py-2 px-3">
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Catatan</label>
                                <input type="text" name="note" value="{{ $expense->note }}" placeholder="Deskripsi pengeluaran" class="shadow border rounded w-full py-2 px-3">
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg transition">
                                Update Pengeluaran
                            </button>
                            <a href="{{ route('expenses.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>