<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
            <h2 class="fw-bold text-dark m-0 fs-4 fs-md-3">
                <i class="fas fa-chart-pie text-success me-2"></i>Dashboard AgriFlow
            </h2>
            <div class="text-dark small">
                <i class="fas fa-calendar-alt me-1"></i>{{ now()->format('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="container-fluid px-2 px-sm-3 px-md-4 py-2 py-sm-3 py-md-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; overflow-x: hidden; width: 100%;">

        <!-- Welcome Card -->
        <div class="card bg-white bg-opacity-95 border-0 shadow-lg mb-3 mb-md-4" style="backdrop-filter: blur(10px); width: 100%;">
            <div class="card-body p-3 p-md-4 text-center">
                <h3 class="fw-bold text-dark mb-0 fs-6 fs-sm-5 fs-md-4 fs-lg-3" style="word-break: break-word;">
                    SELAMAT DATANG, {{ strtoupper(Auth::user()->name) }}!
                </h3>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-2 g-sm-3 g-md-4 mb-3 mb-md-4 mx-0">
            @php
            // Data pengeluaran per bulan 2026
            $monthlyExpenses2026 = [];
            $totalExpense2026 = 0;
            for($m = 1; $m <= 12; $m++) {
                $monthlyTotal=\App\Models\Expense::where('user_id', Auth::id())
                ->whereYear('date', 2026)
                ->whereMonth('date', $m)
                ->sum('amount');
                $monthlyExpenses2026[$m] = $monthlyTotal;
                $totalExpense2026 += $monthlyTotal;
                }

                // Data pendapatan per bulan 2026 dari panen Mentimun
                $monthlyRevenue2026 = [];
                $totalRevenue2026 = 0;
                for($m = 1; $m <= 12; $m++) {
                    $monthlyRevenue=\App\Models\Harvest::where('user_id', Auth::id())
                    ->whereYear('date', 2026)
                    ->whereMonth('date', $m)
                    ->whereHas('plant', function($q) {
                    $q->where('name', 'like', '%timun%')
                    ->orWhere('name', 'like', '%mentimun%');
                    })
                    ->get()
                    ->sum(function($harvest) {
                    return $harvest->weight_kg * $harvest->price_per_kg;
                    });
                    $monthlyRevenue2026[$m] = $monthlyRevenue;
                    $totalRevenue2026 += $monthlyRevenue;
                    }

                    // Hitung keuntungan per bulan (Pendapatan - Pengeluaran)
                    $monthlyProfit2026 = [];
                    for($m = 1; $m <= 12; $m++) {
                        $monthlyProfit2026[$m]=$monthlyRevenue2026[$m] - $monthlyExpenses2026[$m];
                        }

                        $totalProfit2026=$totalRevenue2026 - $totalExpense2026;
                        $totalExpenseApril=$monthlyExpenses2026[4] ?? 0;

                        // Nama bulan
                        $namaBulan=[
                        1=> 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];

                        // Bulan saat ini
                        $currentMonth = date('n'); // 1-12

                        // Prepare chart data untuk JavaScript
                        $expenseChartData = json_encode(array_values($monthlyExpenses2026));

                        // Selected month untuk filter
                        $selectedExpenseMonth = request('expense_month', 'all');
                        $selectedProfitMonth = request('profit_month', 'all');
                        @endphp

                        <div class="col-12 col-sm-6 col-md-4 px-1 px-sm-2">
                            <div class="card bg-white border-0 shadow-lg stat-card h-100" style="border-left: 4px solid #2e7d32;">
                                <div class="card-body p-2 p-sm-3 p-md-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-secondary small mb-1">Total Panen Minggu Ini</p>
                                            <h3 class="fw-bold text-dark mb-1 mb-sm-2 fs-5 fs-sm-4 fs-md-3">{{ number_format($totalWeeklyKg, 1) }} <small class="text-secondary fs-6">kg</small></h3>
                                            <p class="mb-0 text-success fw-semibold small">
                                                <i class="fas fa-chart-line"></i>
                                                <span class="d-inline-block text-truncate" style="max-width: 120px;">Rp {{ number_format($totalWeeklyRevenue, 0, ',', '.') }}</span>
                                            </p>
                                        </div>
                                        <i class="fas fa-box fa-2x fa-sm-3x text-success opacity-50 ms-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 px-1 px-sm-2">
                            <div class="card bg-white border-0 shadow-lg stat-card h-100" style="border-left: 4px solid #ffc107;">
                                <div class="card-body p-2 p-sm-3 p-md-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-secondary small mb-1">Panen Hari Ini</p>
                                            @if($todayHarvests->isEmpty())
                                            <p class="text-secondary mb-1 small">✨ Belum ada panen</p>
                                            <a href="{{ route('harvests.create') }}" class="btn btn-sm btn-success py-0 py-sm-1 px-2">
                                                <i class="fas fa-plus"></i> Input
                                            </a>
                                            @else
                                            @foreach($todayHarvests as $harvest)
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <span class="fw-bold text-dark small">{{ $harvest->plant->name }}</span>
                                                <span class="badge bg-success">{{ $harvest->weight_kg }} kg</span>
                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                        <i class="fas fa-tractor fa-2x fa-sm-3x text-warning opacity-50 ms-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-12 col-md-4 px-1 px-sm-2 mt-2 mt-sm-2 mt-md-0">
                            <div class="card bg-white border-0 shadow-lg stat-card h-100" style="border-left: 4px solid #dc3545;">
                                <div class="card-body p-2 p-sm-3 p-md-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-secondary small mb-1">Total Pengeluaran 2026</p>
                                            <h3 class="fw-bold mb-1 text-danger fs-6 fs-sm-5 fs-md-4" style="word-break: break-word;">
                                                Rp {{ number_format($totalExpense2026, 0, ',', '.') }}
                                            </h3>
                                            <p class="mb-0 text-secondary small">
                                                <i class="fas fa-chart-pie"></i> Total operasional
                                            </p>
                                        </div>
                                        <i class="fas fa-chart-pie fa-2x fa-sm-3x text-danger opacity-50 ms-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
        </div>

        <!-- Tabel Pengeluaran per Bulan 2026 - Dengan Filter -->
        <div class="card bg-white border-0 shadow-lg mb-3 mb-md-4 w-100">
            <div class="card-header bg-white border-0 pt-3 pt-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                    <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">
                        <i class="fas fa-table text-danger me-2"></i>Rekapitulasi Pengeluaran - 2026
                    </h5>

                    <!-- Filter Bulan -->
                    <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2">
                        <input type="hidden" name="profit_month" value="{{ $selectedProfitMonth }}">
                        <select name="expense_month" class="form-select form-select-sm rounded-lg px-2 py-1 border" style="font-size: 0.75rem;" onchange="this.form.submit()">
                            <option value="all" {{ $selectedExpenseMonth == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                            <option value="1" {{ $selectedExpenseMonth == '1' ? 'selected' : '' }}>Januari</option>
                            <option value="2" {{ $selectedExpenseMonth == '2' ? 'selected' : '' }}>Februari</option>
                            <option value="3" {{ $selectedExpenseMonth == '3' ? 'selected' : '' }}>Maret</option>
                            <option value="4" {{ $selectedExpenseMonth == '4' ? 'selected' : '' }}>April</option>
                            <option value="5" {{ $selectedExpenseMonth == '5' ? 'selected' : '' }}>Mei</option>
                            <option value="6" {{ $selectedExpenseMonth == '6' ? 'selected' : '' }}>Juni</option>
                            <option value="7" {{ $selectedExpenseMonth == '7' ? 'selected' : '' }}>Juli</option>
                            <option value="8" {{ $selectedExpenseMonth == '8' ? 'selected' : '' }}>Agustus</option>
                            <option value="9" {{ $selectedExpenseMonth == '9' ? 'selected' : '' }}>September</option>
                            <option value="10" {{ $selectedExpenseMonth == '10' ? 'selected' : '' }}>Oktober</option>
                            <option value="11" {{ $selectedExpenseMonth == '11' ? 'selected' : '' }}>November</option>
                            <option value="12" {{ $selectedExpenseMonth == '12' ? 'selected' : '' }}>Desember</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="card-body p-2 p-md-4">
                <!-- Mobile Card View (untuk layar <768px) -->
                <div class="d-md-none">
                    @if($selectedExpenseMonth == 'all')
                    <!-- Tampilkan Ringkasan Total -->
                    <div class="text-center mb-3">
                        <div class="p-3 rounded bg-danger bg-opacity-10">
                            <div class="display-6 mb-2">💰</div>
                            <div class="fw-bold text-danger fs-4">
                                Rp {{ number_format($totalExpense2026, 0, ',', '.') }}
                            </div>
                            <div class="small text-muted">Total Pengeluaran 2026</div>
                        </div>
                    </div>
                    <div class="border-bottom pb-2 mb-2">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Total Pengeluaran 2026:</span>
                            <span class="text-danger fw-bold">Rp {{ number_format($totalExpense2026, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @else
                    <!-- Tampilkan detail bulan tertentu -->
                    @php
                    $bulanTerpilih = (int)$selectedExpenseMonth;
                    $total = $monthlyExpenses2026[$bulanTerpilih] ?? 0;
                    $percentage = $totalExpense2026 > 0 ? round(($total / $totalExpense2026) * 100, 1) : 0;
                    $isCurrentMonth = ($bulanTerpilih == $currentMonth);
                    @endphp
                    <div class="text-center mb-3">
                        <h6 class="fw-bold text-dark">{{ $namaBulan[$bulanTerpilih] }} 2026</h6>
                        <div class="p-3 rounded bg-danger bg-opacity-10">
                            <div class="display-6 mb-2">📊</div>
                            <div class="fw-bold text-danger fs-4">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </div>
                            <div class="small text-muted">Total Pengeluaran</div>
                        </div>
                    </div>
                    <div class="border-bottom pb-2 mb-2">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Pengeluaran:</span>
                            <span class="text-danger fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Persentase dari Total:</span>
                            <span class="fw-bold">{{ $percentage }}%</span>
                        </div>
                        @if($isCurrentMonth)
                        <div class="d-flex justify-content-between small mt-1">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-danger">Bulan Ini</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Tombol Reset -->
                    @if($selectedExpenseMonth != 'all')
                    <div class="text-center mt-2">
                        <a href="{{ route('dashboard', ['profit_month' => $selectedProfitMonth]) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Lihat Semua Bulan
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Desktop Table View (untuk layar >=768px) -->
                <div class="d-none d-md-block">
                    @if($selectedExpenseMonth == 'all')
                    <!-- Tabel Ringkasan Total -->
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-dark small">Keterangan</th>
                                <th class="text-dark text-end small">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-dark small">Total Pengeluaran Operasional 2026</td>
                                <td class="text-end text-danger fw-bold small">Rp {{ number_format($totalExpense2026, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    @else
                    <!-- Tabel detail bulan tertentu -->
                    @php
                    $bulanTerpilih = (int)$selectedExpenseMonth;
                    $total = $monthlyExpenses2026[$bulanTerpilih] ?? 0;
                    $percentage = $totalExpense2026 > 0 ? round(($total / $totalExpense2026) * 100, 1) : 0;
                    $isCurrentMonth = ($bulanTerpilih == $currentMonth);
                    @endphp
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-dark small">Keterangan</th>
                                <th class="text-dark text-end small">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-dark small">Pengeluaran - {{ $namaBulan[$bulanTerpilih] }} 2026</td>
                                <td class="text-end text-danger fw-bold small">Rp {{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="text-dark small">Persentase dari Total</td>
                                <td class="text-end fw-bold small">{{ $percentage }}%</td>
                            </tr>
                            @if($isCurrentMonth)
                            <tr>
                                <td class="text-dark small">Status</td>
                                <td class="text-end"><span class="badge bg-danger">Bulan Ini</span></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <!-- Tombol Reset -->
                    <div class="text-end mt-2">
                        <a href="{{ route('dashboard', ['profit_month' => $selectedProfitMonth]) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset ke Semua Bulan
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Grafik Pengeluaran per Bulan -->
                <div class="mt-3 mt-md-4 pt-2 pt-md-3">
                    <h6 class="fw-bold text-dark mb-2 mb-md-3 fs-6">
                        <i class="fas fa-chart-bar text-danger me-2"></i>Grafik Pengeluaran 2026
                    </h6>
                    <div style="position: relative; height: 200px;">
                        <canvas id="expenseChart" style="width: 100% !important; height: auto !important;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Panen - Responsive -->
        <div class="card bg-white border-0 shadow-lg mb-3 mb-md-4 w-100">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2 mb-md-3 gap-2">
                    <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">
                        <i class="fas fa-chart-line text-success me-2"></i>Grafik Panen 7 Hari Terakhir
                    </h5>
                    <span class="badge bg-secondary align-self-start align-self-md-center">Update real-time</span>
                </div>
                <div style="position: relative; height: 250px;">
                    <canvas id="harvestChart" style="width: 100% !important; height: auto !important;"></canvas>
                </div>
            </div>
        </div>

        <!-- Two Column Layout - Responsive Stack -->
        <div class="row g-2 g-sm-3 g-md-4 mx-0">
            <div class="col-12 col-lg-8 px-1 px-sm-2">
                <div class="card bg-white border-0 shadow-lg w-100">
                    <div class="card-body p-2 p-sm-3 p-md-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2 mb-md-3 gap-2">
                            <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">
                                <i class="fas fa-list me-2"></i>10 Panen Terakhir
                            </h5>
                            <a href="{{ route('harvests.index') }}" class="btn btn-sm btn-outline-success py-1">
                                Lihat semua <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-dark small">Tanggal</th>
                                        <th class="text-dark small">Tanaman</th>
                                        <th class="text-dark small d-none d-sm-table-cell">Berat</th>
                                        <th class="text-dark text-end small">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentHarvests as $harvest)
                                    <tr>
                                        <td class="text-dark small" style="white-space: nowrap;">{{ $harvest->date->format('d/m/Y') }}</td>
                                        <td class="small"><span class="badge bg-success bg-opacity-10 text-success">{{ $harvest->plant->name }}</span></td>
                                        <td class="text-dark small d-none d-sm-table-cell">{{ number_format($harvest->weight_kg, 1) }} kg</td>
                                        <td class="fw-bold text-success text-end small" style="white-space: nowrap;">Rp {{ number_format($harvest->weight_kg * $harvest->price_per_kg, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 py-md-4">
                                            <i class="fas fa-inbox fa-2x text-secondary mb-2 d-block"></i>
                                            <span class="text-dark small">Belum ada data panen</span>
                                            <a href="{{ route('harvests.create') }}" class="d-block mt-2 small">Input panen pertama</a>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 px-1 px-sm-2">
                <!-- Kartu Keuntungan Mentimun - Responsive dengan Filter -->
                <div class="card bg-white border-0 shadow-lg mb-3 mb-md-4 w-100">
                    <div class="card-header bg-white border-0 pt-3 pt-md-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                            <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">
                                <i class="fas fa-chart-line text-success me-2"></i>Keuntungan Mentimun 2026
                            </h5>

                            <!-- Filter Bulan -->
                            <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2">
                                <input type="hidden" name="expense_month" value="{{ $selectedExpenseMonth }}">
                                <select name="profit_month" class="form-select form-select-sm rounded-lg px-2 py-1 border" style="font-size: 0.75rem;" onchange="this.form.submit()">
                                    <option value="all" {{ $selectedProfitMonth == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                                    <option value="1" {{ $selectedProfitMonth == '1' ? 'selected' : '' }}>Januari</option>
                                    <option value="2" {{ $selectedProfitMonth == '2' ? 'selected' : '' }}>Februari</option>
                                    <option value="3" {{ $selectedProfitMonth == '3' ? 'selected' : '' }}>Maret</option>
                                    <option value="4" {{ $selectedProfitMonth == '4' ? 'selected' : '' }}>April</option>
                                    <option value="5" {{ $selectedProfitMonth == '5' ? 'selected' : '' }}>Mei</option>
                                    <option value="6" {{ $selectedProfitMonth == '6' ? 'selected' : '' }}>Juni</option>
                                    <option value="7" {{ $selectedProfitMonth == '7' ? 'selected' : '' }}>Juli</option>
                                    <option value="8" {{ $selectedProfitMonth == '8' ? 'selected' : '' }}>Agustus</option>
                                    <option value="9" {{ $selectedProfitMonth == '9' ? 'selected' : '' }}>September</option>
                                    <option value="10" {{ $selectedProfitMonth == '10' ? 'selected' : '' }}>Oktober</option>
                                    <option value="11" {{ $selectedProfitMonth == '11' ? 'selected' : '' }}>November</option>
                                    <option value="12" {{ $selectedProfitMonth == '12' ? 'selected' : '' }}>Desember</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-2 p-sm-3 p-md-4">
                        <!-- Mobile Card View -->
                        <div class="d-md-none">
                            @if($selectedProfitMonth == 'all')
                            <!-- Tampilkan Ringkasan Total -->
                            <div class="text-center mb-3">
                                <div class="p-3 rounded {{ $totalProfit2026 >= 0 ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                    <div class="display-6 mb-2">{{ $totalProfit2026 >= 0 ? '🚜' : '⚠️' }}</div>
                                    <div class="fw-bold {{ $totalProfit2026 >= 0 ? 'text-success' : 'text-danger' }} fs-4">
                                        Rp {{ number_format(abs($totalProfit2026), 0, ',', '.') }}
                                    </div>
                                    <div class="small text-muted">{{ $totalProfit2026 >= 0 ? 'Keuntungan Bersih' : 'Kerugian Bersih' }}</div>
                                </div>
                            </div>

                            <!-- Ringkasan Total -->
                            <div class="border-bottom pb-2 mb-2">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>Total Pendapatan:</span>
                                    <span class="text-success fw-bold">Rp {{ number_format($totalRevenue2026, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>Total Pengeluaran:</span>
                                    <span class="text-danger fw-bold">Rp {{ number_format($totalExpense2026, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between small pt-2 border-top">
                                    <span class="fw-bold">Keuntungan Bersih:</span>
                                    <span class="fw-bold {{ $totalProfit2026 >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $totalProfit2026 >= 0 ? '📈' : '📉' }} Rp {{ number_format(abs($totalProfit2026), 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            @else
                            <!-- Tampilkan detail bulan tertentu -->
                            @php
                            $bulanTerpilih = (int)$selectedProfitMonth;
                            $revenue = $monthlyRevenue2026[$bulanTerpilih] ?? 0;
                            $expense = $monthlyExpenses2026[$bulanTerpilih] ?? 0;
                            $profit = $revenue - $expense;
                            $profitClass = $profit >= 0 ? 'text-success' : 'text-danger';
                            $profitIcon = $profit >= 0 ? '📈' : '📉';
                            @endphp
                            <div class="text-center mb-3">
                                <h6 class="fw-bold text-dark">{{ $namaBulan[$bulanTerpilih] }} 2026</h6>
                                <div class="p-3 rounded {{ $profit >= 0 ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                    <div class="display-6 mb-2">{{ $profit >= 0 ? '🚜' : '⚠️' }}</div>
                                    <div class="fw-bold {{ $profitClass }} fs-4">
                                        {{ $profitIcon }} Rp {{ number_format(abs($profit), 0, ',', '.') }}
                                    </div>
                                    <div class="small text-muted">{{ $profit >= 0 ? 'Keuntungan Bulan Ini' : 'Kerugian Bulan Ini' }}</div>
                                </div>
                            </div>
                            <div class="border-bottom pb-2 mb-2">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>Pendapatan:</span>
                                    <span class="text-success fw-bold">Rp {{ number_format($revenue, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>Pengeluaran:</span>
                                    <span class="text-danger fw-bold">Rp {{ number_format($expense, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between small pt-2 border-top">
                                    <span class="fw-bold">Keuntungan:</span>
                                    <span class="fw-bold {{ $profitClass }}">{{ $profitIcon }} Rp {{ number_format(abs($profit), 0, ',', '.') }}</span>
                                </div>
                            </div>
                            @endif

                            <!-- Tombol Reset -->
                            @if($selectedProfitMonth != 'all')
                            <div class="text-center mt-2">
                                <a href="{{ route('dashboard', ['expense_month' => $selectedExpenseMonth]) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-undo me-1"></i> Lihat Semua
                                </a>
                            </div>
                            @endif
                        </div>

                        <!-- Desktop Table View -->
                        <div class="d-none d-md-block">
                            @if($selectedProfitMonth == 'all')
                            <!-- Tabel Ringkasan Total -->
                            <table class="table table-sm table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-dark small">Keterangan</th>
                                        <th class="text-dark text-end small">Jumlah</th>
                            </table>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-dark small">Total Pendapatan Mentimun 2026</td>
                                    <td class="text-end text-success fw-bold small">Rp {{ number_format($totalRevenue2026, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark small">Total Pengeluaran Operasional 2026</td>
                                    <td class="text-end text-danger fw-bold small">Rp {{ number_format($totalExpense2026, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark fw-bold small">Keuntungan / (Kerugian) Bersih</td>
                                    <td class="text-end fw-bold {{ $totalProfit2026 >= 0 ? 'text-success' : 'text-danger' }} small">
                                        {{ $totalProfit2026 >= 0 ? '📈' : '📉' }} Rp {{ number_format(abs($totalProfit2026), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                            @else
                            <!-- Tabel detail bulan tertentu -->
                            @php
                            $bulanTerpilih = (int)$selectedProfitMonth;
                            $revenue = $monthlyRevenue2026[$bulanTerpilih] ?? 0;
                            $expense = $monthlyExpenses2026[$bulanTerpilih] ?? 0;
                            $profit = $revenue - $expense;
                            $profitClass = $profit >= 0 ? 'text-success' : 'text-danger';
                            $profitIcon = $profit >= 0 ? '📈' : '📉';
                            @endphp
                            <table class="table table-sm table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-dark small">Keterangan</th>
                                        <th class="text-dark text-end small">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-dark small">Pendapatan - {{ $namaBulan[$bulanTerpilih] }} 2026</td>
                                        <td class="text-end text-success fw-bold small">Rp {{ number_format($revenue, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-dark small">Pengeluaran - {{ $namaBulan[$bulanTerpilih] }} 2026</td>
                                        <td class="text-end text-danger fw-bold small">Rp {{ number_format($expense, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-dark fw-bold small">Keuntungan / (Kerugian)</td>
                                        <td class="text-end fw-bold {{ $profitClass }} small">
                                            {{ $profitIcon }} Rp {{ number_format(abs($profit), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Tombol Reset -->
                            <div class="text-end mt-2">
                                <a href="{{ route('dashboard', ['expense_month' => $selectedExpenseMonth]) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-undo me-1"></i> Reset ke Semua Bulan
                                </a>
                            </div>
                            @endif
                        </div>

                        <!-- Ringkasan Keuntungan Mini - Responsive -->
                        <div class="mt-2 mt-md-3 p-2 p-md-3 rounded {{ $totalProfit2026 >= 0 ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Laba/Rugi Bersih 2026</small>
                                    <h5 class="{{ $totalProfit2026 >= 0 ? 'text-success' : 'text-danger' }} mb-0 fs-6 fs-md-5">
                                        {{ $totalProfit2026 >= 0 ? '💚' : '💔' }} Rp {{ number_format(abs($totalProfit2026), 0, ',', '.') }}
                                    </h5>
                                    <small class="text-muted">{{ $totalProfit2026 >= 0 ? 'Untung' : 'Rugi' }}</small>
                                </div>
                                <div class="text-center">
                                    <div class="fs-1 fs-md-2">{{ $totalProfit2026 >= 0 ? '🚜' : '⚠️' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips AI Card - Responsive -->
                <div class="card border-0 shadow-lg text-white w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3">
                            <h5 class="fw-bold mb-0 fs-6 fs-md-5">
                                <i class="fas fa-lightbulb me-2"></i>Tips Hari Ini
                            </h5>
                            <i class="fas fa-robot fa-2x opacity-50"></i>
                        </div>
                        <div id="aiTip" class="mb-2 mb-md-4" style="min-height: 80px;">
                            <div class="text-center py-2 py-md-3">
                                <div class="spinner-border text-light spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('chatbot.index') }}" class="btn btn-light w-100 fw-semibold py-1 py-md-2 small" style="color: #667eea;">
                            <i class="fas fa-comment-dots me-2"></i>Tanya AI Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        /* Reset untuk mencegah horizontal scroll */
        body {
            overflow-x: hidden !important;
            width: 100% !important;
        }

        .container-fluid {
            overflow-x: hidden !important;
        }

        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        [class*="col-"] {
            padding-left: 0.25rem !important;
            padding-right: 0.25rem !important;
        }

        @media (min-width: 576px) {
            [class*="col-"] {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
        }

        @media (min-width: 768px) {
            [class*="col-"] {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
        }

        /* Card styles */
        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        .progress {
            border-radius: 10px;
            background-color: #e9ecef;
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 0.5s ease;
        }

        /* Mobile specific */
        @media (max-width: 576px) {
            .fs-1 {
                font-size: 1.3rem !important;
            }

            .fs-2 {
                font-size: 1.1rem !important;
            }

            .fs-3 {
                font-size: 1rem !important;
            }

            .fs-4 {
                font-size: 0.9rem !important;
            }

            .fs-5 {
                font-size: 0.85rem !important;
            }

            .fs-6 {
                font-size: 0.75rem !important;
            }

            .badge {
                font-size: 0.6rem !important;
                padding: 0.2rem 0.4rem !important;
            }

            .btn-sm {
                font-size: 0.65rem !important;
                padding: 0.2rem 0.4rem !important;
            }

            .card-body {
                padding: 0.75rem !important;
            }
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #764ba2;
            border-radius: 10px;
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Canvas responsive */
        canvas {
            max-width: 100% !important;
            height: auto !important;
        }

        /* Form select small */
        .form-select-sm {
            font-size: 0.75rem;
            padding: 0.25rem 1.5rem 0.25rem 0.5rem;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Panen
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('harvestChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            let chartLabels = [];
            let chartValues = [];

            <?php if (isset($chartData) && !empty($chartData)): ?>
                chartLabels = <?php echo json_encode(array_column($chartData, 'date')); ?>;
                chartValues = <?php echo json_encode(array_column($chartData, 'kg')); ?>;
            <?php else: ?>
                chartLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                chartValues = [0, 0, 0, 0, 0, 0, 0];
            <?php endif; ?>

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Hasil Panen (kg)',
                        data: chartValues,
                        borderColor: '#2e7d32',
                        backgroundColor: 'rgba(46, 125, 50, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#2e7d32',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1.5,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                font: {
                                    size: window.innerWidth < 576 ? 9 : 11
                                }
                            }
                        },
                        tooltip: {
                            bodyFont: {
                                size: window.innerWidth < 576 ? 10 : 12
                            },
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' kg';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Berat (kg)',
                                font: {
                                    size: window.innerWidth < 576 ? 9 : 11
                                }
                            },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 576 ? 8 : 10
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Tanggal',
                                font: {
                                    size: window.innerWidth < 576 ? 9 : 11
                                }
                            },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 576 ? 8 : 10
                                }
                            }
                        }
                    }
                }
            });
        });

        // Chart Pengeluaran per Bulan
        document.addEventListener('DOMContentLoaded', function() {
            const expenseCanvas = document.getElementById('expenseChart');
            if (!expenseCanvas) return;

            const expenseCtx = expenseCanvas.getContext('2d');

            let expenseChartData = <?php echo $expenseChartData; ?>;

            const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

            new Chart(expenseCtx, {
                type: 'bar',
                data: {
                    labels: bulanLabels,
                    datasets: [{
                        label: 'Pengeluaran (Rp)',
                        data: expenseChartData,
                        backgroundColor: 'rgba(220, 53, 69, 0.7)',
                        borderColor: 'rgb(220, 53, 69)',
                        borderWidth: 1.5,
                        borderRadius: 6,
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
                            labels: {
                                font: {
                                    size: window.innerWidth < 576 ? 9 : 11
                                }
                            }
                        },
                        tooltip: {
                            bodyFont: {
                                size: window.innerWidth < 576 ? 10 : 12
                            },
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
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
                                    size: window.innerWidth < 576 ? 9 : 11
                                }
                            },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 576 ? 8 : 10
                                },
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                    else if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                    return 'Rp ' + value;
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Bulan',
                                font: {
                                    size: window.innerWidth < 576 ? 9 : 11
                                }
                            },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 576 ? 8 : 10
                                }
                            }
                        }
                    }
                }
            });
        });

        // Fetch AI Tip
        async function fetchAITip() {
            const tipContainer = document.getElementById('aiTip');
            if (!tipContainer) return;
            try {
                const response = await fetch('{{ route("chatbot.ask") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        question: 'Berikan 1 tips singkat untuk petani cabai dan timun hari ini dalam 1 kalimat'
                    })
                });
                const data = await response.json();
                tipContainer.innerHTML = '<i class="fas fa-quote-left me-2 opacity-75"></i> <span class="small">' + (data.reply || '✨ Jaga kelembaban tanah dan perhatikan serangan hama ya, Bu!') + '</span>';
            } catch (error) {
                tipContainer.innerHTML = '<i class="fas fa-quote-left me-2 opacity-75"></i> <span class="small">✨ Jangan lupa catat hasil panen hari ini untuk pantau keuntungan!</span>';
            }
        }
        fetchAITip();
    </script>
</x-app-layout>