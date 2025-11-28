@extends('layouts.master_layout')

@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center py-5"
        style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card-custom p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="bi bi-wallet2 text-primary-custom" style="font-size: 3rem;"></i>
                            </div>
                            <h2 class="fw-bold mb-1">Welcome Back</h2>
                            <p class="text-muted">Please sign in to continue to My Monitor</p>
                        </div>

                        <!-- Toggle Buttons -->
                        <div class="d-flex bg-light rounded p-1 mb-4" id="formToggleButtons">
                            <button type="button" class="btn w-50 fw-medium shadow-sm bg-white text-primary-custom"
                                id="showSignInForm">Sign In</button>
                            <button type="button" class="btn w-50 fw-medium text-muted" id="showSignUpForm">Sign Up</button>
                        </div>

                        <!-- Sign In Form -->
                        <form name="submit" method="post" action="/users/sign_in" id="signInForm">
                            {{ csrf_field() }}

                            <div class="mb-3">
                                <label for="user_name"
                                    class="form-label fw-medium text-secondary small text-uppercase ls-1">Email
                                    Address</label>
                                <input type="text" id="user_name" class="form-control form-control-custom" name="user_name"
                                    placeholder="name@example.com" required>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="password"
                                        class="form-label fw-medium text-secondary small text-uppercase ls-1">Password</label>
                                    <a href="#" class="text-primary-custom text-decoration-none small fw-medium">Forgot
                                        password?</a>
                                </div>
                                <input type="password" id="password" class="form-control form-control-custom"
                                    name="password" placeholder="Enter your password" required>
                            </div>

                            <button type="submit" name="submit_sign_in" class="btn btn-primary-custom w-100 mb-3">
                                Sign In
                            </button>
                        </form>

                        <!-- Sign Up Form (Hidden initially) -->
                        <form method="POST" action="/users/sign_up" id="signUpForm" class="d-none">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="first_name"
                                        class="form-label fw-medium text-secondary small text-uppercase ls-1">First
                                        Name</label>
                                    <input type="text" name="first_name" id="first_name"
                                        class="form-control form-control-custom" value="{{ old('first_name') }}" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="last_name"
                                        class="form-label fw-medium text-secondary small text-uppercase ls-1">Last
                                        Name</label>
                                    <input type="text" name="last_name" id="last_name"
                                        class="form-control form-control-custom" value="{{ old('last_name') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email"
                                    class="form-label fw-medium text-secondary small text-uppercase ls-1">Email
                                    Address</label>
                                <input type="email" name="email" id="email" class="form-control form-control-custom"
                                    value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium text-secondary small text-uppercase ls-1 mb-2">Family
                                    Setup</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check card-radio flex-fill">
                                        <input class="form-check-input d-none" type="radio" name="family_option"
                                            id="create_family" value="create" {{ old('family_option') == 'create' ? 'checked' : '' }} required>
                                        <label
                                            class="form-check-label w-100 p-3 border rounded text-center cursor-pointer transition-all"
                                            for="create_family">
                                            <i class="bi bi-house-add d-block fs-4 mb-1"></i>
                                            <span class="small fw-medium">Create New</span>
                                        </label>
                                    </div>
                                    <div class="form-check card-radio flex-fill">
                                        <input class="form-check-input d-none" type="radio" name="family_option"
                                            id="join_family" value="join" {{ old('family_option') == 'join' ? 'checked' : '' }} required>
                                        <label
                                            class="form-check-label w-100 p-3 border rounded text-center cursor-pointer transition-all"
                                            for="join_family">
                                            <i class="bi bi-people d-block fs-4 mb-1"></i>
                                            <span class="small fw-medium">Join Existing</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3" id="family_name_input" style="display: none;">
                                <label for="family_name"
                                    class="form-label fw-medium text-secondary small text-uppercase ls-1">Family
                                    Name</label>
                                <input type="text" name="family_name" id="family_name"
                                    class="form-control form-control-custom" value="{{ old('family_name') }}"
                                    placeholder="e.g. The Smiths" />
                            </div>

                            <div class="mb-3" id="family_id_input" style="display: none;">
                                <label for="family_id"
                                    class="form-label fw-medium text-secondary small text-uppercase ls-1">Family ID</label>
                                <input type="text" name="family_id" id="family_id" class="form-control form-control-custom"
                                    value="{{ old('family_id') }}" placeholder="Enter Family ID" />
                            </div>

                            <div class="mb-3">
                                <label for="password_signup"
                                    class="form-label fw-medium text-secondary small text-uppercase ls-1">Password</label>
                                <input type="password" name="password" id="password_signup"
                                    class="form-control form-control-custom" required>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation"
                                    class="form-label fw-medium text-secondary small text-uppercase ls-1">Confirm
                                    Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control form-control-custom" required>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" {{ old('terms') ? 'checked' : '' }} required>
                                <label class="form-check-label text-muted small" for="terms">
                                    I agree to the <a href="#" class="text-primary-custom text-decoration-none">Terms of
                                        Service</a> and <a href="#" class="text-primary-custom text-decoration-none">Privacy
                                        Policy</a>.
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary-custom w-100">
                                Create Account
                            </button>
                        </form>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted small">&copy; {{ date('Y') }} My Monitor. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .ls-1 {
            letter-spacing: 0.05em;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .transition-all {
            transition: all 0.2s ease;
        }

        .card-radio input:checked+label {
            border-color: var(--primary-color) !important;
            background-color: rgba(99, 102, 241, 0.05);
            color: var(--primary-color);
        }

        .card-radio label:hover {
            border-color: #cbd5e1;
            background-color: #f8fafc;
        }
    </style>

    <script>
        const signInForm = document.getElementById('signInForm');
        const signUpForm = document.getElementById('signUpForm');
        const showSignInBtn = document.getElementById('showSignInForm');
        const showSignUpBtn = document.getElementById('showSignUpForm');
        const familyNameInput = document.getElementById('family_name_input');
        const familyIdInput = document.getElementById('family_id_input');

        function toggleForm(showSignIn) {
            if (showSignIn) {
                signInForm.classList.remove('d-none');
                signUpForm.classList.add('d-none');

                showSignInBtn.classList.add('bg-white', 'shadow-sm', 'text-primary-custom');
                showSignInBtn.classList.remove('text-muted');

                showSignUpBtn.classList.remove('bg-white', 'shadow-sm', 'text-primary-custom');
                showSignUpBtn.classList.add('text-muted');
            } else {
                signInForm.classList.add('d-none');
                signUpForm.classList.remove('d-none');

                showSignUpBtn.classList.add('bg-white', 'shadow-sm', 'text-primary-custom');
                showSignUpBtn.classList.remove('text-muted');

                showSignInBtn.classList.remove('bg-white', 'shadow-sm', 'text-primary-custom');
                showSignInBtn.classList.add('text-muted');
            }
        }

        showSignInBtn.addEventListener('click', () => toggleForm(true));
        showSignUpBtn.addEventListener('click', () => toggleForm(false));

        // Family Toggle Logic
        document.getElementById('create_family').addEventListener('change', function () {
            if (this.checked) {
                familyNameInput.style.display = 'block';
                familyIdInput.style.display = 'none';
            }
        });

        document.getElementById('join_family').addEventListener('change', function () {
            if (this.checked) {
                familyNameInput.style.display = 'none';
                familyIdInput.style.display = 'block';
            }
        });
    </script>
@endsection