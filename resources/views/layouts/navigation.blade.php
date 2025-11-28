<nav class="navbar navbar-dark fixed-top"
    style="background: linear-gradient(135deg, #6a11cb, #2575fc); box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <div class="container-fluid">
        <!-- Brand with Logo -->
        <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="/">
            <i class="bi bi-graph-up-arrow me-2 fs-4"></i>
            <span style="letter-spacing: 0.5px;">{{ env('APP_NAME') }}</span>
        </a>

        <!-- Offcanvas Button -->
        <button class="navbar-toggler border-0 focus-ring focus-ring-light" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Offcanvas Sidebar -->
        <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasNavbar"
            style="background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);">
            <div class="offcanvas-header border-bottom border-secondary">
                <h5 class="offcanvas-title text-white fw-bold">
                    <i class="bi bi-list me-2"></i> Menu
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
            </div>

            <div class="offcanvas-body d-flex flex-column h-100 p-0">
                <!-- User Info Section -->
                <div class="p-4 bg-opacity-10 bg-white mb-2">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white shadow"
                            style="width: 48px; height: 48px; font-size: 1.5rem;">
                            {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold text-white">{{ auth()->user()->first_name }}</h6>
                            <small class="text-white-50">User</small>
                        </div>
                    </div>

                    <!-- Family ID Card -->
                    <div class="card bg-dark bg-opacity-50 border border-secondary">
                        <div class="card-body p-2 d-flex justify-content-between align-items-center">
                            <div class="text-truncate me-2">
                                <small class="text-secondary d-block" style="font-size: 0.7rem;">FAMILY ID</small>
                                <span class="font-monospace text-light" id="familyId"
                                    style="font-size: 0.9rem;">{{ auth()->user()->family_id }}</span>
                            </div>
                            <button class="btn btn-sm btn-outline-primary border-0" onclick="copyFamilyId(this)"
                                title="Copy ID">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Menu Items -->
                <ul class="navbar-nav flex-grow-1 px-3">
                    <li class="nav-item mb-1">
                        <a class="nav-link text-white px-3 py-2 rounded hover-bg-primary d-flex align-items-center"
                            href="/normal_transactions">
                            <i class="bi bi-wallet2 me-3 fs-5 text-info"></i> Normal Transactions
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link text-white px-3 py-2 rounded hover-bg-primary d-flex align-items-center"
                            href="/draft_transactions">
                            <i class="bi bi-pencil-square me-3 fs-5 text-warning"></i> Draft Transactions
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link text-white px-3 py-2 rounded hover-bg-primary d-flex align-items-center"
                            href="/blueprint_transactions">
                            <i class="bi bi-collection me-3 fs-5 text-success"></i> Blueprint Transactions
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link text-white px-3 py-2 rounded hover-bg-primary d-flex align-items-center"
                            href="/categories">
                            <i class="bi bi-tags me-3 fs-5 text-danger"></i> Categories
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link text-white px-3 py-2 rounded hover-bg-primary d-flex align-items-center"
                            href="/wallets">
                            <i class="bi bi-wallet me-3 fs-5 text-primary"></i> Wallets
                        </a>
                    </li>
                </ul>

                <!-- Sign Out Button -->
                <div class="p-3 mt-auto border-top border-secondary">
                    <form method="POST" action="/users/sign_out">
                        {{ csrf_field() }}
                        <button type="submit"
                            class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center py-2">
                            <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    .hover-bg-primary {
        transition: all 0.2s ease;
    }

    .hover-bg-primary:hover {
        background-color: rgba(37, 117, 252, 0.15);
        transform: translateX(5px);
    }

    .focus-ring-light:focus {
        box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
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