<nav class="navbar navbar-dark fixed-top" style="background: linear-gradient(135deg, #6a11cb, #2575fc);">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fw-bold text-white" href="/">
            <i class="bi bi-bar-chart-line"></i> {{ env("APP_NAME") }}
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

            <div class="offcanvas-body flex-grow-1">
                <ul class="navbar-nav">
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

                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold" href="/users/process_sign_out">
                            <i class="bi bi-box-arrow-right"></i> Sign Out
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Footer -->
            <div class="offcanvas-footer text-center p-3 mt-auto bg-secondary text-white rounded-bottom">
                &copy; {{ date('Y') }} My Monitor | Developed by Khaled Mostafa
            </div>
        </div>
    </div>
</nav>
