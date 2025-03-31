<nav class="navbar navbar-dark fixed-top" style="background: linear-gradient(135deg, #6a11cb, #2575fc);">
    <div class="container-fluid">
        <!-- Brand with Logo -->
        <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="/">
            {{ env("APP_NAME") }}
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
                <ul class="navbar-nav flex-grow-1">
                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold" href="/transactions">
                            <i class="bi bi-wallet2"></i> Transactions
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

            <!-- Footer -->
            <div class="offcanvas-footer text-center p-3 bg-secondary text-white rounded-bottom">
                &copy; {{ date('Y') }} My Monitor | Developed by Khaled Mostafa
            </div>
        </div>
    </div>
</nav>
