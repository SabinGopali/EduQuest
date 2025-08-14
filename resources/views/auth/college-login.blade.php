@extends('layouts.app')

@section('content')

<head>
    <style>
        body {
            background-color: white;
            margin-top: 90px;
        }
        .login-card {
            background: white;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 2rem auto;
        }

        .login-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #000;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-check {
            margin-bottom: 24px;
        }

        .form-group label {
            font-weight: 700;
            color: #000;
            margin-bottom: 6px;
            display: block;
        }

        input[type="email"],
        input[type="password"] {
            font-weight: 400;
            color: #000;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #007BFF;
            box-shadow: 0 0 6px rgba(0, 123, 255, 0.4);
            background-color: #f0f8ff;
        }

        .form-check-label {
            font-weight: 600;
            color: #000;
        }

        .btn-primary {
            background-color: white;
            border: 2px solid black;
            padding: 10px 24px;
            font-weight: 700;
            border-radius: 6px;
            color: black;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-right: 12px;
            margin-bottom: 12px;
        }
        .btn-primary:hover {
            background-color: black;
            color: white;
        }

        .btn-link {
            font-weight: 600;
            color: #007BFF;
            text-decoration: none;
            margin-bottom: 12px;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .alert {
            font-weight: 600;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 25px 20px;
                max-width: 90vw;
                margin: 1.5rem auto;
            }

            .d-flex.flex-wrap.align-items-center {
                flex-direction: column;
                align-items: stretch;
            }

            .d-flex.flex-wrap.align-items-center > * {
                margin-right: 0 !important;
                margin-bottom: 12px;
                width: 100%;
            }

            .d-flex.flex-wrap.align-items-center > *:last-child {
                margin-bottom: 0;
            }

            .btn-primary {
                width: 100%;
                margin-right: 0;
            }
        }
    </style>
</head>

<div class="login-card">
    <div class="login-title">{{ __('College Login') }}</div>

    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('college.login') }}">
        @csrf

        <div class="form-group">
            <label for="email">{{ __('E-Mail Address') }}</label>
            <input id="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">{{ __('Password') }}</label>
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="current-password">
            @error('password')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
                {{ __('Remember Me') }}
            </label>
        </div>

        <div class="d-flex flex-wrap align-items-center">
            <button type="submit" class="btn btn-primary">
                {{ __('Login') }}
            </button>

            @if (Route::has('admin.password.request'))
                <a class="btn btn-link mt-3 mt-md-0 ms-md-auto" href="{{ route('admin.password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
        </div>
    </form>
</div>

@endsection
