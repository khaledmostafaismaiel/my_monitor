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
                                <i class="bi bi-shield-lock text-primary-custom" style="font-size: 3rem;"></i>
                            </div>
                            <h2 class="fw-bold mb-1">Verify Identity</h2>
                            <p class="text-muted">Enter the 6-digit code sent to your device</p>
                        </div>

                        <form method="POST" action="/users/verify_otp">
                            {{ csrf_field() }}

                            <div class="d-flex justify-content-between gap-2 mb-4">
                                @for($i = 1; $i <= 6; $i++)
                                    <input type="text" name="otp{{ $i }}" id="otp{{ $i }}"
                                        class="otp-input form-control form-control-custom text-center fs-4 fw-bold text-primary-custom"
                                        maxlength="1" required {{ $i === 1 ? 'autofocus' : '' }}
                                        style="height: 60px; width: 50px; padding: 0;">
                                @endfor
                            </div>

                            <button type="submit" class="btn btn-primary-custom w-100 mb-3">
                                Verify Code
                            </button>

                            <div class="text-center">
                                <p class="text-muted small mb-0">Didn't receive the code? <a href="#"
                                        class="text-primary-custom text-decoration-none fw-medium">Resend</a></p>
                            </div>
                        </form>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted small">&copy; {{ date('Y') }} My Monitor. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Automatically focus on the next input after a character is entered
        const inputs = document.querySelectorAll('.otp-input');

        inputs.forEach((input, index) => {
            input.addEventListener('input', function () {
                if (this.value.length === 1) {
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                }
            });

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace' && this.value.length === 0) {
                    if (index > 0) {
                        inputs[index - 1].focus();
                    }
                }
            });
        });
    </script>
@endsection