<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - เข้าสู่ระบบ</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: #f3f4f6;
        }
        .login-container {
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .left-section {
            flex: 1;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18rem;
            position: relative;
        }
        .right-section {
            flex: 1;
            background-color: #ffffff;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2rem 2rem 10rem;
            box-shadow: -4px 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            margin-top: 0.5rem;
            font-size: 1rem;
        }
        .login-btn {
            width: 100%;
            background-color: #10b981;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 0.375rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            font-size: 1rem;
        }
        .login-btn:hover {
            background-color: #059669;
        }
        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
            padding: 2rem;
            border-radius: 1rem;
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .logo-img {
            width: 80px;
            height: auto;
            margin-bottom: 1rem;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: 1;
        }
        .content-wrapper {
            position: relative;
            z-index: 2;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .left-section {
                padding: 1.5rem;
            }
            .right-section {
                padding: 1.5rem;
            }
            .login-box {
                padding: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            .left-section {
                min-height: 250px;
                padding: 1rem;
            }
            .right-section {
                box-shadow: none;
                padding: 1rem;
            }
            .logo-section {
                padding: 1rem;
                margin-bottom: 1rem;
            }
            .logo-img {
                width: 60px;
            }
            h1 {
                font-size: 1.5rem !important;
            }
            h2 {
                font-size: 1.25rem !important;
            }
            p {
                font-size: 1rem !important;
            }
        }

        @media (max-width: 480px) {
            .left-section {
                min-height: 200px;
            }
            .login-box {
                padding: 1rem;
            }
            .form-input {
                padding: 0.5rem;
                font-size: 0.875rem;
            }
            .login-btn {
                padding: 0.5rem;
                font-size: 0.875rem;
            }
            footer {
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Section - Image -->
        <div class="left-section" style="background-image: url('{{ asset('backend/assets/images/sru-president.jpg') }}'); background-size: cover; background-position: center;">
            <div class="logo-section" style="background-color: rgba(255, 255, 255, 0.7); padding: 3rem; border-radius: 1rem;">
                <div class="logo-bg" style="background-color: rgba(255, 255, 255, 0.8); border-radius: 50%; padding: 10px; display: inline-block;">
                    <img src="{{ asset('uploads/logo.png') }}" alt="SRU Logo" class="logo-img" style="display: block; margin: 0 auto;">
                </div>
                <h1 class="text-3xl mt-4 font-bold text-gray-900 mb-2">SRU e-Meeting</h1>
                <p class="text-lg text-gray-700">ระบบบริหารจัดการเอกสารประชุมออนไลน์</p>
            </div>
        </div>

        <!-- Right Section - Login Form -->
        <div class="right-section">
            <div class="login-box">
                <div class="logo-section bg-transparent">
                    <h2 class="text-3xl font-bold text-gray-900">เข้าสู่ระบบ</h2>
                    <p class="text-lg text-gray-600 mt-2">กรุณาเข้าสู่ระบบด้วยบัญชีของคุณ</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <x-input-label for="email" :value="__('อีเมล')" />
                        <x-text-input id="email"
                            class="form-input"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            placeholder="อีเมลของคุณ"
                            autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <x-input-label for="password" :value="__('รหัสผ่าน')" />
                        <div class="relative">
                            <x-text-input id="password"
                                class="form-input pr-10"
                                type="password"
                                name="password"
                                required
                                placeholder="รหัสผ่าน"
                                autocomplete="current-password" />
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center" style="top: 50%; transform: translateY(-50%); right: 10px;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="form-group flex items-center">
                        <label class="inline-flex items-center">
                            <input type="checkbox"
                                class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                                name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('จดจำการเข้าสู่ระบบ') }}</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="login-btn" onclick="this.classList.add('loading')">
                            <span class="button-text">{{ __('เข้าสู่ระบบ SRU e-Meeting') }}</span>
                            <span class="loading-spinner"></span>
                        </button>
                    </div>
                    <style>
                        .login-btn {
                            position: relative;
                            overflow: hidden;
                            transition: all 0.3s ease;
                        }
                        .login-btn.loading .button-text {
                            opacity: 0;
                        }
                        .login-btn.loading .loading-spinner {
                            opacity: 1;
                        }
                        .loading-spinner {
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            width: 20px;
                            height: 20px;
                            border: 2px solid #ffffff;
                            border-top: 2px solid transparent;
                            border-radius: 50%;
                            opacity: 0;
                            transition: opacity 0.3s ease;
                            animation: spin 1s linear infinite;
                        }
                        @keyframes spin {
                            0% { transform: translate(-50%, -50%) rotate(0deg); }
                            100% { transform: translate(-50%, -50%) rotate(360deg); }
                        }
                    </style>
                </form>

                <hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #666; margin: 1.5rem 0;">

                <footer class="text-center mt-8 text-md text-gray-600">
                    <p>&copy; พัฒนาโดย ศูนย์นวัตกรรมและเทคโนโลยีดิจิทัล IDTC {{ date('Y') }}.</p>
                    <p>All rights reserved. Version : 1.1</p>
                </footer>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Change the eye icon
                if (type === 'text') {
                    toggleButton.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                            <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                        </svg>
                    `;
                } else {
                    toggleButton.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                    `;
                }
            });
        });
    </script>
</body>
</html>
