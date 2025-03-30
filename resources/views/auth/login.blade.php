@extends('layouts.master_layout')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100" style="background: linear-gradient(135deg, #6a11cb, #2575fc);">
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
@endsection
