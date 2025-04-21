<nav class="navbar navbar-dark fixed-top" style="background: linear-gradient(135deg, #6a11cb, #2575fc);">
    <div class="container-fluid">
        <!-- Brand with Logo -->
        <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="/">
            <i class="bi bi-graph-up-arrow me-2"></i> {{ env('APP_NAME') }}
        </a>

        <!-- Offcanvas Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Offcanvas Sidebar -->
        <div class="offcanvas offcanvas-end text-bg-dark d-flex flex-column" tabindex="-1" id="offcanvasNavbar">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title text-white">Menu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
            </div>

            <div class="offcanvas-body d-flex flex-column h-100">
                <!-- User Info Section -->
                <div class="text-white mt-3 mb-4 d-flex flex-column">
                    <!-- User Icon and Name -->
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-person-circle me-2" style="font-size: 1.5rem;"></i>
                        <p class="fw-bold mb-0">{{ auth()->user()->first_name }}</p>
                    </div>
                    <!-- Family ID below the Name with Copy Icon -->
                    <div class="d-flex align-items-center">
                        <small class="text-light me-2">
                            Family ID: <span id="familyId">{{ auth()->user()->family_id }}</span>
                        </small>
                        <button class="btn btn-sm btn-outline-light py-0 px-1" onclick="copyFamilyId()" title="Copy ID">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>

                <!-- Divider to separate user info from the menu -->
                <hr class="text-white">

                <!-- Menu Items -->
                <ul class="navbar-nav flex-grow-1">
                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold" href="/normal_transactions">
                            <i class="bi bi-wallet2"></i> Normal Transactions
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold" href="/draft_transactions">
                            <i class="bi bi-pencil-square"></i> Drafted Transactions
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold" href="/blueprint_transactions">
                            <i class="bi bi-collection"></i> Blueprint Transactions
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold" href="/categories">
                            <i class="bi bi-tags"></i> Categories
                        </a>
                    </li>
                </ul>

                <!-- Sign Out Button at the Bottom -->
                <div class="mt-auto">
                    <form method="POST" action="/users/sign_out">
                        {{ csrf_field() }}
                        <button type="submit" class="nav-link text-danger fw-bold bg-transparent border-0">
                            <i class="bi bi-box-arrow-right"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Copy Script -->
<script>
    function copyFamilyId() {
        const familyId = document.getElementById('familyId').textContent;
        navigator.clipboard.writeText(familyId);
    }
</script>
