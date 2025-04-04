@extends('layouts.master_layout')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 px-3" style="background: linear-gradient(135deg, #6a11cb, #2575fc);">
    <div class="card p-4 shadow-lg rounded" style="max-width: 400px; width: 100%;">
        <div class="text-center">
            <h1 class="fw-bold text-primary">My Monitor</h1>
            <p class="text-muted">Track your transactions with ease</p>
        </div>

        <form method="POST" action="/users/sign_up">
            {{ csrf_field() }}

            <fieldset>
                <legend class="text-center mb-3">
                    <h2 class="text-dark"><i class="bi bi-wallet2"></i> Sign Up</h2>
                </legend>

                <div class="mb-3">
                    <label for="first_name" class="form-label fw-bold">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control rounded-pill px-3" value="{{ old('first_name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="last_name" class="form-label fw-bold">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control rounded-pill px-3" value="{{ old('last_name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email</label>
                    <input type="email" name="email" id="email" class="form-control rounded-pill px-3" value="{{ old('email') }}" required>
                </div>

                <!-- Radio buttons for Family creation or joining an existing family -->
                <div class="mb-3">
                    <label class="fw-bold">Family</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="family_option" id="create_family" value="create" {{ old('family_option') == 'create' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="create_family">
                            Create New Family
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="family_option" id="join_family" value="join" {{ old('family_option') == 'join' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="join_family">
                            Join Existing Family
                        </label>
                    </div>


                    <!-- Family Name input for creating a new family -->
                    <div class="mb-3" id="family_name_input" style="display: none;">
                        <label for="family_name" class="form-label fw-bold">Family Name</label>
                        <input type="text" name="family_name" id="family_name" class="form-control rounded-pill px-3" value="{{ old('family_name') }}" placeholder="Enter Family Name" />
                    </div>

                    <!-- Family ID input for joining an existing family -->
                    <div class="mb-3" id="family_id_input" style="display: none;">
                        <label for="family_id" class="form-label fw-bold">Family ID</label>
                        <input type="text" name="family_id" id="family_id" class="form-control rounded-pill px-3" value="{{ old('family_id') }}" placeholder="Enter Family ID" />
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Password</label>
                    <input type="password" name="password" id="password" class="form-control rounded-pill px-3" value="{{ old('password') }}" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-pill px-3" value="{{ old('password_confirmation') }}" required>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="terms" id="terms" {{ old('terms') ? 'checked' : '' }} required>
                    <label class="form-check-label fw-bold text-dark" for="terms">
                        I accept all <a href="#" class="text-decoration-underline" style="color: #6a11cb;">Terms and Conditions</a>.
                    </label>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill shadow">Sign Up</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<!-- JavaScript to toggle Family Name and Family ID input visibility -->
<script>
    document.getElementById('join_family').addEventListener('change', function() {
        document.getElementById('family_id_input').style.display = 'block';
        document.getElementById('family_name_input').style.display = 'none';
    });

    document.getElementById('create_family').addEventListener('change', function() {
        document.getElementById('family_id_input').style.display = 'none';
        document.getElementById('family_name_input').style.display = 'block';
    });
</script>

<style>
    @media (max-width: 576px) { 
        .card {
            padding: 2rem 1.5rem;
            max-width: 90%;
        }
        h1 {
            font-size: 1.8rem;
        }
        h2 {
            font-size: 1.4rem;
        }
        .form-control {
            font-size: 1rem;
        }
    }

    @media (min-width: 768px) {
        .card {
            max-width: 450px;
        }
    }

    @media (min-width: 1200px) {
        .card {
            max-width: 400px;
        }
    }
</style>
@endsection
