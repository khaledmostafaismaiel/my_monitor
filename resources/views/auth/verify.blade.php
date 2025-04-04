@extends('layouts.master_layout')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 px-3" style="background: linear-gradient(135deg, #6a11cb, #2575fc);">
    <div class="card p-4 shadow-lg rounded" style="max-width: 400px; width: 100%;">
        <div class="text-center">
            <h1 class="fw-bold text-primary">My Monitor</h1>
            <p class="text-muted">Verify your identity</p>
        </div>

        <form method="POST" action="/users/verify_otp">
            {{ csrf_field() }}

            <fieldset>
                <legend class="text-center mb-3">
                    <h2 class="text-dark"><i class="bi bi-lock"></i> Enter OTP</h2>
                </legend>

                <div class="mb-3 text-center">
                    <p class="text-dark">Please enter the 6-digit OTP sent to your email/phone.</p>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <input type="text" name="otp1" id="otp1" class="otp-input form-control rounded-pill px-3" maxlength="1" required autofocus>
                    <input type="text" name="otp2" id="otp2" class="otp-input form-control rounded-pill px-3" maxlength="1" required>
                    <input type="text" name="otp3" id="otp3" class="otp-input form-control rounded-pill px-3" maxlength="1" required>
                    <input type="text" name="otp4" id="otp4" class="otp-input form-control rounded-pill px-3" maxlength="1" required>
                    <input type="text" name="otp5" id="otp5" class="otp-input form-control rounded-pill px-3" maxlength="1" required>
                    <input type="text" name="otp6" id="otp6" class="otp-input form-control rounded-pill px-3" maxlength="1" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill shadow">Verify OTP</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<!-- JavaScript for OTP input behavior -->
<script>
    // Automatically focus on the next input after a character is entered
    document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
        input.addEventListener('input', function() {
            if (input.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });
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
