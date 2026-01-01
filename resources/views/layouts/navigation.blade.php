<nav class="navbar navbar-dark fixed-top shadow-sm" style="background: var(--secondary-color);">
    <div class="container-fluid">
        <!-- Brand with Logo -->
        <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="/"
            style="font-family: 'Outfit', sans-serif; letter-spacing: 0.5px;">
            <i class="bi bi-graph-up-arrow me-2 fs-4"></i>
            <span>{{ env('APP_NAME') }}</span>
        </a>

        <!-- Offcanvas Button -->
        <button class="navbar-toggler border-0 focus-ring focus-ring-light" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Offcanvas Sidebar -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
            style="background: var(--secondary-color); width: 320px;">
            <div class="offcanvas-header border-bottom border-secondary pb-3">
                <h5 class="offcanvas-title text-white fw-bold" style="font-family: 'Outfit', sans-serif;">
                    Menu
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
            </div>

            <div class="offcanvas-body d-flex flex-column h-100 p-0">
                <!-- User Info Section -->
                <div class="p-4 mb-3" style="background: rgba(255, 255, 255, 0.05);">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm"
                            style="width: 56px; height: 56px; font-size: 1.75rem; background: linear-gradient(135deg, var(--primary-color), #8b5cf6); font-family: 'Outfit', sans-serif;">
                            {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold text-white" style="font-family: 'Outfit', sans-serif;">
                                {{ auth()->user()->first_name }}
                            </h6>
                            <small class="text-white-50" style="font-family: 'Inter', sans-serif;">Member</small>
                        </div>
                    </div>

                    <!-- Family ID Card -->
                    <div class="card border-0 shadow-sm" style="background: rgba(255, 255, 255, 0.08);">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div class="text-truncate me-2">
                                <small class="text-white-50 d-block text-uppercase fw-bold"
                                    style="font-size: 0.65rem; letter-spacing: 0.05em; font-family: 'Inter', sans-serif;">Family
                                    ID</small>
                                <span class="font-monospace text-white" id="familyId"
                                    style="font-size: 0.9rem;">{{ auth()->user()->family_id }}</span>
                            </div>
                            <button class="btn btn-sm btn-outline-light border-0 rounded-circle p-2"
                                onclick="copyFamilyId(this)" title="Copy ID" style="width: 32px; height: 32px;">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Menu Items -->
                <ul class="navbar-nav flex-grow-1 px-3" style="font-family: 'Inter', sans-serif;">
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white px-3 py-3 rounded-3 nav-link-custom d-flex align-items-center"
                            href="/normal_transactions">
                            <i class="bi bi-wallet2 me-3 fs-5 text-info"></i>
                            <span>Normal Transactions</span>
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a class="nav-link text-white px-3 py-3 rounded-3 nav-link-custom d-flex align-items-center"
                            href="/draft_transactions">
                            <i class="bi bi-pencil-square me-3 fs-5 text-warning"></i>
                            <span>Draft Transactions</span>
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a class="nav-link text-white px-3 py-3 rounded-3 nav-link-custom d-flex align-items-center"
                            href="/blueprint_transactions">
                            <i class="bi bi-collection me-3 fs-5 text-success"></i>
                            <span>Blueprint Transactions</span>
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a class="nav-link text-white px-3 py-3 rounded-3 nav-link-custom d-flex align-items-center"
                            href="/categories">
                            <i class="bi bi-tags me-3 fs-5 text-danger"></i>
                            <span>Categories</span>
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a class="nav-link text-white px-3 py-3 rounded-3 nav-link-custom d-flex align-items-center"
                            href="/wallets">
                            <i class="bi bi-wallet me-3 fs-5" style="color: var(--primary-color);"></i>
                            <span>Wallets</span>
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a class="nav-link text-white px-3 py-3 rounded-3 nav-link-custom d-flex align-items-center"
                            href="/todos">
                            <i class="bi bi-check2-square me-3 fs-5 text-primary"></i>
                            <span>Todos</span>
                        </a>
                    </li>
                </ul>

                <!-- Sign Out Button -->
                <div class="p-3 mt-auto border-top" style="border-color: rgba(255, 255, 255, 0.1) !important;">
                    <form method="POST" action="/users/sign_out">
                        {{ csrf_field() }}
                        <button type="submit"
                            class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center py-2 rounded-3 fw-semibold"
                            style="font-family: 'Inter', sans-serif; transition: all 0.3s ease;">
                            <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    .nav-link-custom {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .nav-link-custom::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: var(--primary-color);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .nav-link-custom:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }

    .nav-link-custom:hover::before {
        transform: scaleY(1);
    }

    .nav-link-custom:hover i {
        transform: scale(1.1);
    }

    .nav-link-custom i {
        transition: transform 0.3s ease;
    }

    .focus-ring-light:focus {
        box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }
</style>

<script>
    function copyFamilyId(btn) {
        const familyId = document.getElementById('familyId').textContent;
        navigator.clipboard.writeText(familyId).then(() => {
            const icon = btn.querySelector('i');
            const originalClass = icon.className;

            // Change icon to checkmark
            icon.className = 'bi bi-check-lg text-success';

            // Revert back after 2 seconds
            setTimeout(() => {
                icon.className = originalClass;
            }, 2000);
        });
    }
</script>