<nav class="navbar bg-primary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">{{env("APP_NAME")}}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            
            <div class="offcanvas-body">

                @include('layouts/search_box')

                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/transactions">Transactions</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/categories">Categories</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/users/process_sign_out">Signout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>