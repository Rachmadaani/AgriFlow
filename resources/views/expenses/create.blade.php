<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Input Pengeluaran') }}
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

            <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-6">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                    <h3 class="text-white font-bold text-lg">💰 Form Input Pengeluaran</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('expenses.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal <span class="text-red-500">*</span></label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="shadow border rounded-lg w-full py-2 px-3 focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500">
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kategori <span class="text-red-500">*</span></label>
                                <select name="category" required class="shadow border rounded-lg w-full py-2 px-3 focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500">
                                    <option value="">Pilih Kategori</option>
                                    <option value="bibit">🌱 Bibit</option>
                                    <option value="pupuk">💊 Pupuk</option>
                                    <option value="pestisida">🐛 Pestisida</option>
                                    <option value="transport">🚚 Transportasi</option>
                                    <option value="tenaga kerja">👨‍🌾 Tenaga Kerja</option>
                                    <option value="alat">🔧 Alat & Perlengkapan</option>
                                    <option value="lainnya">📦 Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="amount" step="1000" placeholder="Contoh: 50000" required class="shadow border rounded-lg w-full py-2 px-3 focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500">
                                <p class="text-xs text-gray-500 mt-1">Masukkan angka tanpa titik atau koma</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Catatan</label>
                                <input type="text" name="note" placeholder="Deskripsi pengeluaran (opsional)" class="shadow border rounded-lg w-full py-2 px-3 focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500">
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-3 px-6 rounded-lg transition transform hover:scale-105 shadow-md">
                                💰 Simpan Pengeluaran
                            </button>
                            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ringkasan Pengeluaran per Bulan (2026) -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 px-6 py-4">
                    <div class="flex justify-between items-center flex-wrap gap-3">
                        <h4 class="text-white font-bold text-lg">
                            <i class="fas fa-chart-pie me-2"></i>Ringkasan Pengeluaran 2026
                        </h4>

                        <!-- Filter Bulan -->
                        <form method="GET" action="{{ route('expenses.create') }}" class="flex gap-2">
                            <select name="bulan" class="form-select rounded-lg px-3 py-2 border-0 shadow-sm" onchange="this.form.submit()">
                                <option value="1" {{ request('bulan', date('m')) == '1' ? 'selected' : '' }}>Januari</option>
                                <option value="2" {{ request('bulan', date('m')) == '2' ? 'selected' : '' }}>Februari</option>
                                <option value="3" {{ request('bulan', date('m')) == '3' ? 'selected' : '' }}>Maret</option>
                                <option value="4" {{ request('bulan', date('m')) == '4' ? 'selected' : '' }}>April</option>
                                <option value="5" {{ request('bulan', date('m')) == '5' ? 'selected' : '' }}>Mei</option>
                                <option value="6" {{ request('bulan', date('m')) == '6' ? 'selected' : '' }}>Juni</option>
                                <option value="7" {{ request('bulan', date('m')) == '7' ? 'selected' : '' }}>Juli</option>
                                <option value="8" {{ request('bulan', date('m')) == '8' ? 'selected' : '' }}>Agustus</option>
                                <option value="9" {{ request('bulan', date('m')) == '9' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ request('bulan', date('m')) == '10' ? 'selected' : '' }}>Oktober</option>
                                <option value="11" {{ request('bulan', date('m')) == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ request('bulan', date('m')) == '12' ? 'selected' : '' }}>Desember</option>
                            </select>
                            <button type="submit" class="btn-filter bg-white text-blue-600 rounded-lg px-3 py-2 border-0 shadow-sm">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </form>
                    </div>
                </div>
                <div class="p-6">
                    @php
                    $bulanTerpilih = request('bulan', date('m'));
                    $tahunTerpilih = 2026;

                    // Data pengeluaran per bulan yang dipilih
                    $monthlyExpenses = App\Models\Expense::where('user_id', Auth::id())
                    ->whereYear('date', $tahunTerpilih)
                    ->whereMonth('date', $bulanTerpilih)
                    ->orderBy('date', 'desc')
                    ->get();
                    $totalMonthly = $monthlyExpenses->sum('amount');
                    $byCategory = $monthlyExpenses->groupBy('category');

                    // Nama bulan
                    $namaBulan = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];

                    // Data untuk chart summary per bulan (Jan-Des 2026)
                    $monthlyTotals = [];
                    for($m = 1; $m <= 12; $m++) {
                        $monthlyTotals[]=App\Models\Expense::where('user_id', Auth::id())
                        ->whereYear('date', 2026)
                        ->whereMonth('date', $m)
                        ->sum('amount');
                        }

                        // Convert ke JSON dengan aman
                        $chartDataJson = json_encode($monthlyTotals);
                        @endphp

                        <!-- Statistik Bulan Terpilih -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-3">
                                <h5 class="font-bold text-gray-800">
                                    📊 {{ $namaBulan[(int)$bulanTerpilih] }} {{ $tahunTerpilih }}
                                </h5>
                                <span class="text-xs text-gray-400">{{ $monthlyExpenses->count() }} transaksi</span>
                            </div>

                            @if($monthlyExpenses->count() > 0)
                            <!-- Ringkasan per Kategori dengan Note -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                @foreach($byCategory as $category => $items)
                                <div class="bg-gray-50 rounded-lg p-3 hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-700">
                                                @if($category == 'bibit') 🌱 Bibit
                                                @elseif($category == 'pupuk') 💊 Pupuk
                                                @elseif($category == 'pestisida') 🐛 Pestisida
                                                @elseif($category == 'transport') 🚚 Transportasi
                                                @elseif($category == 'tenaga kerja') 👨‍🌾 Tenaga Kerja
                                                @elseif($category == 'alat') 🔧 Alat & Perlengkapan
                                                @else 📦 {{ ucfirst($category) }}
                                                @endif
                                            </div>
                                            <div class="font-bold text-gray-800 text-lg">
                                                Rp {{ number_format($items->sum('amount'), 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs text-gray-400">{{ $items->count() }}x</span>
                                        </div>
                                    </div>
                                    <!-- Tampilkan Note/Catatan -->
                                    @php
                                    $notes = $items->where('note', '!=', '')->pluck('note')->toArray();
                                    @endphp
                                    @if(!empty($notes))
                                    <div class="mt-2 pt-2 border-t border-gray-200">
                                        <div class="text-xs text-gray-500">
                                            <i class="fas fa-sticky-note mr-1"></i> Catatan:
                                            @foreach($notes as $idx => $note)
                                            <span class="inline-block bg-gray-200 rounded px-2 py-0.5 mr-1 mb-1">{{ $note }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>

                            <!-- Contoh Data Statis (Demo) -->
                            <div class="bg-blue-50 rounded-lg p-3 mb-4 border border-blue-200">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="text-sm font-semibold text-blue-700">
                                            <i class="fas fa-info-circle me-1"></i> Contoh Data Bulan Ini
                                        </div>
                                        <div class="flex gap-4 mt-2 flex-wrap">
                                            <div class="bg-white rounded px-3 py-1 shadow-sm">
                                                <span class="text-xs text-gray-500">🌱 Bibit</span>
                                                <span class="font-bold text-gray-800 ml-2">Rp 16.000</span>
                                            </div>
                                            <div class="bg-white rounded px-3 py-1 shadow-sm">
                                                <span class="text-xs text-gray-500">💊 Pupuk</span>
                                                <span class="font-bold text-gray-800 ml-2">Rp 45.000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <i class="fas fa-chart-line text-blue-300 text-2xl"></i>
                                </div>
                            </div>

                            <div class="text-right text-sm text-gray-600 pt-3 border-t">
                                Total Pengeluaran: <span class="font-bold text-yellow-600 text-lg">Rp {{ number_format($totalMonthly, 0, ',', '.') }}</span>
                            </div>
                            @else
                            <div class="text-center py-8">
                                <div class="text-5xl mb-3">💰</div>
                                <p class="text-gray-500">Belum ada data pengeluaran untuk {{ $namaBulan[(int)$bulanTerpilih] }} {{ $tahunTerpilih }}</p>
                                <p class="text-sm text-gray-400 mt-1">Silakan input pengeluaran di form atas</p>

                                <!-- Contoh Data Statis saat kosong -->
                                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-2">Contoh format pengeluaran:</p>
                                    <div class="flex justify-center gap-4 flex-wrap">
                                        <div class="text-sm">🌱 Bibit: <span class="font-bold">Rp 16.000</span></div>
                                        <div class="text-sm">💊 Pupuk: <span class="font-bold">Rp 45.000</span></div>
                                        <div class="text-sm">🐛 Pestisida: <span class="font-bold">Rp 30.000</span></div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Daftar Detail Pengeluaran per Transaksi -->
                        @if($monthlyExpenses->count() > 0)
                        <div class="mt-4">
                            <h6 class="font-semibold text-gray-700 mb-2">
                                <i class="fas fa-list-ul me-1"></i> Detail Transaksi
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="text-dark">Tanggal</th>
                                            <th class="text-dark">Kategori</th>
                                            <th class="text-dark text-end">Jumlah</th>
                                            <th class="text-dark">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($monthlyExpenses as $expense)
                                        <tr>
                                            <td class="text-dark">{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                                            <td>
                                                @if($expense->category == 'bibit') 🌱 Bibit
                                                @elseif($expense->category == 'pupuk') 💊 Pupuk
                                                @elseif($expense->category == 'pestisida') 🐛 Pestisida
                                                @elseif($expense->category == 'transport') 🚚 Transportasi
                                                @elseif($expense->category == 'tenaga kerja') 👨‍🌾 Tenaga Kerja
                                                @elseif($expense->category == 'alat') 🔧 Alat
                                                @else 📦 {{ ucfirst($expense->category) }}
                                                @endif
                                            </td>
                                            <td class="text-end fw-bold text-danger">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                            <td class="text-muted">{{ $expense->note ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-light">
                                        <tr>
                                            <td colspan="2" class="fw-bold">TOTAL</td>
                                            <td class="text-end fw-bold text-danger">Rp {{ number_format($totalMonthly, 0, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        @endif

                        <!-- Grafik Pengeluaran per Bulan 2026 -->
                        <div class="mt-6 pt-6 border-t">
                            <h5 class="font-bold text-gray-800 mb-3">
                                📈 Grafik Pengeluaran per Bulan - Tahun {{ $tahunTerpilih }}
                            </h5>
                            <canvas id="expenseChart" style="max-height: 300px;"></canvas>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Grafik Pengeluaran per Bulan
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('expenseChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            let monthlyData;

            <?php if (isset($chartDataJson) && $chartDataJson): ?>
                monthlyData = <?php echo $chartDataJson; ?>;
            <?php else: ?>
                monthlyData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            <?php endif; ?>

            const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: bulanLabels,
                    datasets: [{
                        label: 'Total Pengeluaran (Rp)',
                        data: monthlyData,
                        backgroundColor: 'rgba(255, 193, 7, 0.7)',
                        borderColor: 'rgb(255, 193, 7)',
                        borderWidth: 2,
                        borderRadius: 8,
                        barPercentage: 0.65,
                        categoryPercentage: 0.8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let value = context.raw;
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Pengeluaran (Rp)',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Bulan',
                                font: {
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

    <style>
        .form-select {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background-color: white;
        }

        .form-select:focus {
            outline: none;
            border-color: #ffc107;
            box-shadow: 0 0 0 2px rgba(255, 193, 7, 0.2);
        }

        .btn-filter {
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-filter:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
        }

        .transition {
            transition: all 0.3s ease;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
        }
    </style>
</x-app-layout>