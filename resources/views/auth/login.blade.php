@extends('layouts.master_layout')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 px-3" style="background: linear-gradient(135deg, #6a11cb, #2575fc);">
    <div class="card p-4 shadow-lg rounded" style="max-width: 400px; width: 100%;">
        <div class="text-center">
            <h1 class="fw-bold text-primary">My Monitor</h1>
            <p class="text-muted">Track your transactions with ease</p>
        </div>

        <form name="submit" method="post" action="/users/sign_in">
            {{ csrf_field() }}

            <fieldset>
                <legend class="text-center mb-3">
                    <h2 class="text-dark"><i class="bi bi-wallet2"></i> Sign In</h2>
                </legend>

                <div class="mb-3">
                    <label for="user_name" class="form-label fw-bold">User Name:</label>
                    <input type="text" id="user_name" class="form-control rounded-pill px-3" name="user_name" placeholder="Your E-mail" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Password:</label>
                    <input type="password" id="password" class="form-control rounded-pill px-3" name="password" placeholder="Your Password" required>
                </div>

                <div class="text-center">
                    <button type="submit" name="submit_sign_in" class="btn btn-primary w-100 rounded-pill shadow">Sign In</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<style>
    @media (max-width: 576px) { 
        /* Adjustments for Small Screens (Phones) */
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
        /* Adjustments for Medium Screens (Tablets) */
        .card {
            max-width: 450px;
        }
    }

    @media (min-width: 1200px) {
        /* Adjustments for Large Screens (Desktops) */
        .card {
            max-width: 400px;
        }
    }
</style>
@endsection
