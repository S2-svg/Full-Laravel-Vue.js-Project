<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — {{ config('app.name', 'GlobalStore') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            color: #fff;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(99, 102, 241, 0.2) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 30%, rgba(168, 85, 247, 0.15) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 1.5rem;
        }

        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-brand-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
            margin-bottom: 0.75rem;
            box-shadow: 0 10px 28px rgba(99, 102, 241, 0.4);
        }

        .login-brand h1 {
            font-weight: 700;
            font-size: 22px;
            margin: 0;
            letter-spacing: -0.3px;
            color: #fff;
        }

        .login-brand p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 14px;
            margin: 0.25rem 0 0;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 2.25rem;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.35);
        }

        .error-alert {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            color: #fca5a5;
            font-size: 14px;
        }

        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 0.4rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            padding: 0.7rem 1rem;
            font-size: 15px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            color: #fff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
            background-image: none;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
        }

        .invalid-feedback {
            color: #fca5a5;
            font-size: 13px;
            margin-top: 0.3rem;
        }

        .input-group-custom {
            position: relative;
        }

        .input-group-custom .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            background: none;
            border: none;
            padding: 4px;
            z-index: 5;
            font-size: 18px;
        }

        .input-group-custom .toggle-password:hover {
            color: rgba(255, 255, 255, 0.75);
        }

        .input-group-custom .form-control {
            padding-right: 42px;
        }

        .form-check-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }

        .form-check-input {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-check-input:checked {
            background-color: #6366f1;
            border-color: #6366f1;
        }

        .btn-login {
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border: none;
            color: #fff;
            padding: 0.75rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            width: 100%;
            transition: all 0.25s ease;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.35);
            margin-top: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(99, 102, 241, 0.5);
            color: #fff;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.35);
        }

        .footer-text a {
            color: #818cf8;
            text-decoration: none;
        }

        .footer-text a:hover {
            color: #a78bfa;
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .login-wrapper {
                padding: 1rem;
            }
            .login-card {
                padding: 1.75rem 1.25rem;
                border-radius: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-brand">
            <div class="login-brand-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h1>Admin Panel</h1>
            <p>Sign in to manage your store</p>
        </div>

        <div class="login-card">
            @if(session('error'))
                <div class="error-alert" role="alert">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/admin/login') }}" autocomplete="off">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required autofocus
                        placeholder="admin@example.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group-custom">
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required placeholder="••••••••">
                        <button type="button" class="toggle-password" data-target="password" aria-label="Toggle password">
                            <i class="bi bi-eye" id="eye-icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2 form-check">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input"
                    {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="form-check-label">Keep me signed in</label>
                </div>

                <button type="submit" class="btn-login">
                    Sign In
                </button>
            </form>
        </div>

        <p class="footer-text">
            &copy; {{ date('Y') }} {{ config('app.name', 'GlobalStore') }}. Admin access only.
        </p>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const target = document.getElementById(this.dataset.target);
                const icon = this.querySelector('i');
                if (target.type === 'password') {
                    target.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    target.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });
    </script>
</body>

</html>
