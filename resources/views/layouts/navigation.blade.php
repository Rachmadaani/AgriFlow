<nav style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 12px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <!-- Logo -->
        <a class="fw-bold text-white text-decoration-none" href="{{ route('dashboard') }}" style="font-size: 1.5rem;">
            <i class="fas fa-seedling me-2"></i>AgriFlow
        </a>

        <!-- Tombol Hamburger untuk Mobile -->
        <button id="hamburgerBtn" style="background: transparent; border: 2px solid white; color: white; font-size: 1.5rem; padding: 5px 12px; border-radius: 8px; cursor: pointer; display: none;">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Menu Desktop -->
        <div id="navMenu" style="display: flex; gap: 5px; flex-wrap: wrap; align-items: center;">
            <a class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-chart-line me-1"></i> Dashboard
            </a>
            <a class="nav-link-custom {{ request()->routeIs('plants.*') ? 'active' : '' }}" href="{{ route('plants.index') }}">
                <i class="fas fa-seedling me-1"></i> Tanaman
            </a>
            <a class="nav-link-custom {{ request()->routeIs('harvests.*') ? 'active' : '' }}" href="{{ route('harvests.create') }}">
                <i class="fas fa-tractor me-1"></i> Input Panen
            </a>
            <a class="nav-link-custom {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.create') }}">
                <i class="fas fa-money-bill-wave me-1"></i> Pengeluaran
            </a>
            <a class="nav-link-custom {{ request()->routeIs('chatbot.*') ? 'active' : '' }}" href="{{ route('chatbot.index') }}">
                <i class="fas fa-robot me-1"></i> AI Tani
            </a>

            <!-- DROPDOWN USER -->
            <div class="dropdown">
                <button class="btn btn-light dropdown-btn">
                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }} <i class="fas fa-chevron-down ms-1"></i>
                </button>
                <div class="dropdown-content">
                    <a href="{{ route('profile.edit') }}">
                        <i class="fas fa-user me-2"></i> Profil Saya
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <i class="fas fa-sign-out-alt me-2"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Nav Link Styles */
    .nav-link-custom {
        color: white;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .nav-link-custom:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .nav-link-custom.active {
        background-color: rgba(255, 255, 255, 0.3);
        font-weight: bold;
    }

    /* Dropdown Styles */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-btn {
        border-radius: 8px;
        padding: 8px 16px;
        cursor: pointer;
        background: white;
        border: none;
    }

    .dropdown-content {
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 5px;
        background: white;
        min-width: 180px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        display: none;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown-content a {
        display: block;
        padding: 12px 20px;
        text-decoration: none;
        color: #333;
        transition: all 0.2s;
        border-radius: 10px 10px 0 0;
    }

    .dropdown-content a:hover {
        background-color: #e8f5e9;
    }

    .dropdown-content button {
        display: block;
        width: 100%;
        text-align: left;
        padding: 12px 20px;
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        transition: all 0.2s;
        border-radius: 0 0 10px 10px;
    }

    .dropdown-content button:hover {
        background-color: #ffebee;
    }

    .dropdown-divider {
        height: 1px;
        background-color: #e9ecef;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        #hamburgerBtn {
            display: block !important;
        }

        #navMenu {
            display: none !important;
            width: 100%;
            flex-direction: column;
            margin-top: 15px;
        }

        #navMenu.show {
            display: flex !important;
        }

        .nav-link-custom,
        .dropdown {
            width: 100%;
            text-align: center;
        }

        .dropdown-btn {
            width: 100%;
        }

        .dropdown-content {
            position: static;
            width: 100%;
            margin-top: 5px;
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: none;
        }
    }
</style>

<script>
    // Mobile menu toggle
    var hamburger = document.getElementById('hamburgerBtn');
    var navMenu = document.getElementById('navMenu');

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            navMenu.classList.toggle('show');
        });
    }

    // Untuk mobile: klik dropdown button
    if (window.innerWidth <= 768) {
        var dropdownBtn = document.querySelector('.dropdown-btn');
        var dropdownContent = document.querySelector('.dropdown-content');

        if (dropdownBtn && dropdownContent) {
            dropdownBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (dropdownContent.style.display === 'block') {
                    dropdownContent.style.display = 'none';
                } else {
                    dropdownContent.style.display = 'block';
                }
            });
        }
    }

    // Reset saat resize window
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            var dropdownContent = document.querySelector('.dropdown-content');
            if (dropdownContent) {
                dropdownContent.style.display = '';
            }
        } else {
            // Mobile mode
            var dropdownContent = document.querySelector('.dropdown-content');
            if (dropdownContent) {
                dropdownContent.style.display = 'none';
            }
        }
    });
</script>