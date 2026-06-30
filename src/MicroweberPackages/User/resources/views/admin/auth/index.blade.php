<?php

/*

type: layout
content_type: static
name: Clean
position: 6
description: Clean

*/

$templateViewsName = template_name();

$extends = 'templates.bootstrap::layouts.master';

if (isset($templateViewsName) and !empty($templateViewsName)) {
    $extendsCheckView = 'templates.' . $templateViewsName . '::layouts.master';
    if (view()->exists($extendsCheckView)) {
        $extends = $extendsCheckView;
    }
}

// Check if user is logged in
$isLoggedIn = is_logged();
$currentUser = null;
if ($isLoggedIn) {
    $currentUser = Auth::user();
}

// Social login providers
$enableFacebook = get_option('enable_user_fb_registration', 'users');
$enableGoogle = get_option('enable_user_google_registration', 'users');
$enableGithub = get_option('enable_user_github_registration', 'users');
$enableLinkedin = get_option('enable_user_linkedin_registration', 'users');
$enableTwitter = get_option('enable_user_twitter_registration', 'users');
$hasSocialLogin = $enableFacebook || $enableGoogle || $enableGithub || $enableLinkedin || $enableTwitter;

// User registration setting
$enableUserRegistration = get_option('enable_user_registration', 'users');
$showRegisterTab = $enableUserRegistration !== 'n' && $enableUserRegistration !== 0;
$redirect = $_GET['redirect'] ?? request()->get('redirect', '');

?>
@extends($extends)

@section('content')

    <style>
        /* task-2026-05-22-093946 / AI-757 — purposeful branded background.
           Makes the full viewport a brand-tinted gradient so the login card
           floats on a purposeful surface rather than inheriting the raw
           template background. Dark mode uses a deep-navy variant so the
           white card remains readable. WCAG AA contrast preserved (4.6:1
           for body text on the white card). */
        .auth-container {
            min-height: 100vh;
            max-width: 100%;
            width: 100%;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            background: linear-gradient(160deg, #e8f0fe 0%, #f0f6ff 60%, #ffffff 100%);
            box-sizing: border-box;
        }
        html.dark .auth-container,
        html.theme-dark .auth-container {
            background: linear-gradient(160deg, #0f172a 0%, #1e293b 60%, #0f172a 100%);
        }

        .auth-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 480px;
            width: 100%;
            padding: 40px;
        }
        html.dark .auth-card,
        html.theme-dark .auth-card {
            background: #1e293b;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
            color: #f1f5f9;
        }
        html.dark .auth-header h2,
        html.theme-dark .auth-header h2 {
            color: #f1f5f9;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        /* task-2026-05-22-1dcb94 / AI-757 — brand logo in auth header */
        .auth-brand-logo-wrapper {
            margin-bottom: 16px;
        }
        .auth-brand-logo-wrapper a {
            display: inline-block;
            text-decoration: none;
        }
        .auth-brand-logo {
            max-height: 60px;
            max-width: 200px;
            width: auto;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .auth-brand-name {
            font-size: 18px;
            font-weight: 700;
            color: #0d6efd;
            text-decoration: none;
            display: inline-block;
        }

        .auth-header h2 {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .auth-tabs {
            display: flex;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 30px;
            gap: 10px;
        }

        .auth-tab {
            flex: 1;
            padding: 12px 20px;
            text-align: center;
            background: transparent;
            border: none;
            color: #6b7280;
            font-weight: 500;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .auth-tab.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        .auth-tab:hover {
            color: #3b82f6;
        }

        .auth-tab-content {
            display: none;
        }

        .auth-tab-content.active {
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 500;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 10px 16px;
            margin-bottom: 10px;
            background: white;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-social:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .btn-social svg {
            width: 20px;
            height: 20px;
        }

        .btn-facebook {
            background: #1877f2;
            color: white;
            border: none;
        }

        .btn-facebook:hover {
            background: #0c63d4;
            color: white;
        }

        .btn-google {
            background: #fff;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-google:hover {
            background: #f9fafb;
        }

        .btn-github {
            background: #24292e;
            color: white;
            border: none;
        }

        .btn-github:hover {
            background: #1a1e22;
            color: white;
        }

        .btn-linkedin {
            background: #0077b5;
            color: white;
            border: none;
        }

        .btn-linkedin:hover {
            background: #005885;
            color: white;
        }

        .btn-twitter {
            background: #1da1f2;
            color: white;
            border: none;
        }

        .btn-twitter:hover {
            background: #0c8bd9;
            color: white;
        }

        .social-login-section {
            margin-bottom: 25px;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0;
            color: #6b7280;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
        }

        .divider span {
            padding: 0 15px;
        }

        .form-footer {
            /* task-2026-05-18-2e95f2 / AI-865 — bumped margin-top 20px → 28px for vertical
               breathing room post-tap-zone enlargement on .form-footer a (designer's
               optional polish, accepted as default since the anchor padding makes the
               prior 20px margin visually too tight). */
            margin-top: 28px;
            text-align: center;
            font-size: 14px;
        }

        .form-footer a {
            color: #3b82f6;
            text-decoration: none;
            /* task-2026-05-18-2e95f2 / AI-865 — WCAG 2.5.5 Target Size compliance.
               Pre-fix `.form-footer a` had no display / min-height / padding override,
               so the 3 in-page anchors collapsed to ~20px height at mobile 390
               (Forgot-your-password 135×20, Sign-in 42×20 worst-case 840 sq-px,
               Back-to-Login 85×20). inline-block + min-height: 44px + 12/16px padding
               hits the AAA 44×44 floor without disrupting the parent
               .form-footer { text-align: center } flow layout. line-height: 20px
               preserves the original visual text rhythm inside the enlarged tap zone. */
            display: inline-block;
            min-height: 44px;
            padding: 12px 16px;
            line-height: 20px;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        /* task-2026-05-22-834339 — WCAG 2.5.5 touch-target floor on
           Remember me + Terms checkboxes. Tester measured 18×18px (checkbox)
           and 93×20px (label) at 390×844 — both below the 44×44 floor.
           Fix: expand the wrapper row to min-height 44px and make the label
           fill it as an inline-flex row. Clicking anywhere on the label
           still activates the checkbox (label[for] association). The native
           checkbox glyph stays its standard size; only the tap area grows.
           Covers both the Login (remember me) and Register (terms) checkboxes
           since both use the same .checkbox-wrapper class. */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            min-height: 44px;
            cursor: pointer;
        }

        .checkbox-wrapper label {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            cursor: pointer;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: auto;
            min-width: 20px;
            min-height: 20px;
            cursor: pointer;
        }

        .logged-in-section {
            text-align: center;
            padding: 20px;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 20px;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #6b7280;
        }

        .user-info h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .user-info p {
            color: #6b7280;
            margin-bottom: 20px;
        }

        .btn-logout {
            background: #ef4444;
            color: white;
        }

        .btn-logout:hover {
            background: #dc2626;
        }

        .btn-dashboard {
            background: #10b981;
            color: white;
            margin-bottom: 10px;
        }

        .btn-dashboard:hover {
            background: #059669;
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.6s linear infinite;
            margin-left: 8px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .alert-container {
            min-height: 20px;
        }
    </style>

    <div class="auth-container">
        <div class="auth-card">
            @if($isLoggedIn && $currentUser)
                <!-- Logged In User Section -->
                <div class="logged-in-section">
                    <div class="user-avatar">
                        @if($currentUser->thumbnail)
                            <img src="{{ $currentUser->thumbnail }}"
                                 alt="{{ $currentUser->first_name ?? $currentUser->username }}"
                                 style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        @else
                            {{ strtoupper(substr($currentUser->first_name ?? $currentUser->username ?? $currentUser->email, 0, 1)) }}
                        @endif
                    </div>

                    <div class="user-info">
                        <h3>{{ $currentUser->first_name ?? $currentUser->username }}</h3>
                        <p>{{ $currentUser->email }}</p>

                        @if($currentUser->is_admin == 1)
                            <a href="{{ admin_url() }}" class="btn btn-dashboard">Go to Dashboard</a>
                        @endif


                        @if($redirect)
                            <a href="{{ $redirect }}" class="btn btn-dashboard">Continue</a>
                        @endif

                        <a href="{{ logout_url() }}" class="btn btn-logout">Logout</a>
                    </div>
                </div>
            @else
                <!-- Login/Register Forms -->
                {{-- task-2026-05-22-1dcb94 / AI-757 — brand logo + brand expression
                     on admin login page. admin_logo_login() returns the configured
                     admin logo URL; falls back to brand name text when no logo is
                     configured. Mirrors the AI-794 pattern from layout.blade.php.
                --}}
                @php $mwAdminLoginLogo = app()->ui->admin_logo_login(); @endphp
                <div class="auth-header">
                    <div class="auth-brand-logo-wrapper">
                        @if ($mwAdminLoginLogo)
                            <a href="{{ url('/') }}" aria-label="{{ app()->ui->brand_name() ?: 'Microweber' }}">
                                <img src="{{ $mwAdminLoginLogo }}"
                                     alt="{{ app()->ui->brand_name() ?: 'Microweber' }}"
                                     class="auth-brand-logo">
                            </a>
                        @else
                            <a href="{{ url('/') }}" class="auth-brand-name" aria-label="{{ app()->ui->brand_name() ?: 'Microweber' }}">
                                {{ app()->ui->brand_name() ?: 'Microweber' }}
                            </a>
                        @endif
                    </div>
                    <h2>Welcome</h2>
                    <p style="color: #6b7280; font-size: 14px;">Sign in to your account or create a new one</p>
                </div>

                <div class="alert-container" id="alertContainer"></div>

                <div class="auth-tabs">
                    <button class="auth-tab active" data-tab="login">Login</button>
                    @if($showRegisterTab)
                        <button class="auth-tab" data-tab="register">Register</button>
                    @endif
                    <button class="auth-tab d-none" data-tab="forgot">Forgot Password</button>
                </div>

                <!-- Login Tab -->
                <div class="auth-tab-content active" id="login">
                    @if($hasSocialLogin)
                        <div class="social-login-section">
                            @if($enableFacebook)
                                <a href="{{ api_url('user_social_login?provider=facebook') }}&redirect={{ urlencode($redirect) }}"
                                   class="btn btn-social btn-facebook">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    Continue with Facebook
                                </a>
                            @endif

                            @if($enableGoogle)
                                <a href="{{ api_url('user_social_login?provider=google') }}&redirect={{ urlencode($redirect) }}"
                                   class="btn btn-social btn-google">
                                    <svg viewBox="0 0 24 24">
                                        <path fill="#4285F4"
                                              d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path fill="#34A853"
                                              d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        <path fill="#FBBC05"
                                              d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                        <path fill="#EA4335"
                                              d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                    </svg>
                                    Continue with Google
                                </a>
                            @endif

                            @if($enableGithub)
                                <a href="{{ api_url('user_social_login?provider=github') }}"
                                   class="btn btn-social btn-github">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                    </svg>
                                    Continue with GitHub
                                </a>
                            @endif

                            @if($enableLinkedin)
                                <a href="{{ api_url('user_social_login?provider=linkedin') }}&redirect={{ urlencode($redirect) }}"
                                   class="btn btn-social btn-linkedin">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                    Continue with LinkedIn
                                </a>
                            @endif

                            @if($enableTwitter)
                                <a href="{{ api_url('user_social_login?provider=twitter') }}&redirect={{ urlencode($redirect) }}"
                                   class="btn btn-social btn-twitter">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                    Continue with Twitter
                                </a>
                            @endif
                        </div>

                        <div class="divider"><span>OR</span></div>
                    @endif

                    <form id="loginForm" method="POST" action="{{ route('api.user.login') }}">
                        @csrf

                        @if($redirect)
                            <input type="hidden" name="redirect" value="{{ $redirect }}"/>
                        @endif


                        <div class="form-group">
                            <label class="form-label">Email or Username</label>
                            {{-- task-2026-05-18-78caa0 / AI-864 — added autocomplete="username"
                                 per WHATWG HTML autocomplete spec §4.10.18.7 — `username` is
                                 the primary account identifier (works for both email and
                                 username login-key shapes). Adjacent audit while fixing the
                                 main Register-tab password defect; see L645/L651 for the
                                 mandatory fix. --}}
                            <input type="text" class="form-control" autocomplete="username" name="username" value="{{ old('username') }}"
                                   required autofocus>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            {{-- L512 — Login Password correctly carries `current-password`
                                 (existing-account sign-in). Untouched by AI-864. --}}
                            <input type="password" class="form-control" autocomplete="current-password" name="password"
                                   required>
                        </div>

                        <div class="form-group">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" name="remember" id="remember">
                                <label for="remember" style="margin: 0; font-weight: normal;">Remember me</label>
                            </div>
                        </div>

                        @if (get_option('captcha_disabled', 'users') !== 'y')
                            <div class="form-group">
                                <module type="captcha" id="login_captcha"/>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary" id="loginBtn">
                            Sign In
                        </button>
                    </form>

                    <div class="form-footer">
                        {{-- task-2026-05-18-77da7a / AI-863 — replace `href="#"` JS-intercept
                             with real route URL so the AI-794 standalone
                             `/forgot-password` chrome (commit 4ecba69fd0) is reachable
                             from the natural navigation. Pre-fix the JS click-handler
                             at the bottom of this file swapped an in-place forgot panel,
                             orphaning the polished standalone chrome. Sibling fix-shape:
                             L672 + L704 below get the same treatment via `route('login')`.
                             JS intercept block was removed in the same commit. --}}
                        <a href="{{ route('password.request') }}" class="forgot-password-link">Forgot your password?</a>
                    </div>
                </div>

                <!-- Register Tab -->
                @if($showRegisterTab)
                    <div class="auth-tab-content" id="register">
                        @if($hasSocialLogin)
                            <div class="social-login-section">
                                @if($enableFacebook)
                                    <a href="{{ api_url('user_social_login?provider=facebook') }}&redirect={{ urlencode($redirect) }}"
                                       class="btn btn-social btn-facebook">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    Sign up with Facebook
                                    </a>
                                @endif

                                @if($enableGoogle)

                                    <a href="{{ api_url('user_social_login?provider=google') }}&redirect={{ urlencode($redirect) }}"
                                    class="btn btn-social btn-google">
                                    <svg viewBox="0 0 24 24">
                                        <path fill="#4285F4"
                                              d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path fill="#34A853"
                                              d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        <path fill="#FBBC05"
                                              d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                        <path fill="#EA4335"
                                              d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                    </svg>
                                    Sign up with Google
                                    </a>
                                @endif

                                @if($enableGithub)
                                    <a href="{{ api_url('user_social_login?provider=github') }}&redirect={{ urlencode($redirect) }}"
                                    class="btn btn-social btn-github">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                    </svg>
                                    Sign up with GitHub
                                    </a>
                                @endif

                                @if($enableLinkedin)
                                    <a href="{{ api_url('user_social_login?provider=linkedin') }}&redirect={{ urlencode($redirect) }}"                                       class="btn btn-social btn-linkedin">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                        Sign up with LinkedIn
                                    </a>
                                @endif

                                @if($enableTwitter)
                                    <a href="{{ api_url('user_social_login?provider=twitter') }}&redirect={{ urlencode($redirect) }}"
                                    class="btn btn-social btn-twitter">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                    Sign up with Twitter
                                    </a>
                                @endif
                            </div>

                            <div class="divider"><span>OR</span></div>
                        @endif

                        <form id="registerForm" method="POST" action="{{ route('api.user.register') }}">
                            @csrf


                            @if($redirect)
                                <input type="hidden" name="redirect" value="{{ $redirect }}"/>
                            @endif


                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                {{-- AI-864 adjacent audit — autocomplete="name" per WHATWG spec
                                     for full-name proper-name autofill. --}}
                                <input type="text" class="form-control" autocomplete="name" name="first_name"
                                       value="{{ old('first_name') }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email</label>
                                {{-- AI-864 adjacent audit — autocomplete="email" per WHATWG spec
                                     for email-input autofill. --}}
                                <input type="email" class="form-control" autocomplete="email" name="email" value="{{ old('email') }}"
                                       required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Username</label>
                                {{-- AI-864 adjacent audit — autocomplete="username" per WHATWG spec
                                     for primary-account-identifier autofill. --}}
                                <input type="text" class="form-control" autocomplete="username" name="username" value="{{ old('username') }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Password</label>
                                {{-- task-2026-05-18-78caa0 / AI-864 — autocomplete="new-password"
                                     per WHATWG HTML autocomplete spec §4.10.18.7. Pre-fix
                                     `current-password` told browsers this was an existing-account
                                     sign-in form, silently pre-filling Register Password with the
                                     user's saved /login password from prior sessions — privacy
                                     leak on shared/public devices + UX failure on the registration
                                     happy path. `new-password` instructs the browser to (a) NOT
                                     autofill prior saved credentials AND (b) offer password-manager
                                     generation for a fresh strong password. --}}
                                <input type="password" class="form-control" autocomplete="new-password"
                                       name="password" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Confirm Password</label>
                                {{-- AI-864 — same fix-shape applied to Confirm Password to
                                     match the primary Password field's autocomplete contract. --}}
                                <input type="password" class="form-control" autocomplete="new-password"
                                       name="password_confirmation" required>
                            </div>

                            @if (get_option('captcha_disabled', 'users') !== 'y')
                                <div class="form-group">
                                    <module type="captcha" id="login_captcha_register"/>
                                </div>
                            @endif

                            @if (get_option('require_terms', 'users') )
                                <div class="form-group">

                                    <div class="checkbox-wrapper">
                                        <input type="checkbox" name="terms" id="agree_terms" required>
                                        <label for="agree_terms" style="margin: 0; font-weight: normal;">
                                            I agree to the <a href="{{ get_option('terms_page_url', 'users') }}"
                                                              target="_blank" rel="noopener noreferrer">Terms and Conditions</a>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary" id="registerBtn">
                                Create Account
                            </button>
                        </form>

                        <div class="form-footer">
                            {{-- AI-863 — real route URL (was href="#" + JS intercept). --}}
                            Already have an account? <a href="{{ route('login') }}" class="login-link">Sign in</a>
                        </div>
                    </div>
                @endif

                <!-- Forgot Password Tab -->
                <div class="auth-tab-content" id="forgot">
                    <form id="forgotForm" method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <p style="margin-bottom: 20px; color: #6b7280; font-size: 14px;">
                            Enter your email address and we'll send you a link to reset your password.
                        </p>

                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            {{-- AI-864 adjacent audit — autocomplete="email" on Forgot Password
                                 email input so the browser autofills the user's saved email. --}}
                            <input type="email" class="form-control" autocomplete="email" name="email" value="{{ old('email') }}" required
                                   autofocus>
                        </div>

                        @if (get_option('captcha_disabled', 'users') !== 'y')
                            <div class="form-group">
                                <module type="captcha" id="login_captcha_forgot"/>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary" id="forgotBtn">
                            Send Reset Link
                        </button>
                    </form>

                    <div class="form-footer">
                        {{-- AI-863 — real route URL (was href="#" + JS intercept). --}}
                        <a href="{{ route('login') }}" class="login-link">Back to Login</a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tab switching
            const tabs = document.querySelectorAll('.auth-tab');
            const tabContents = document.querySelectorAll('.auth-tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    const targetTab = this.getAttribute('data-tab');

                    // Remove active class from all tabs and contents
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    // Add active class to clicked tab and corresponding content
                    this.classList.add('active');
                    document.getElementById(targetTab).classList.add('active');

                    // Clear alerts
                    clearAlerts();
                });
            });

            // task-2026-05-18-77da7a / AI-863 — JS click-intercept handlers for
            // .forgot-password-link + .login-link REMOVED. The handlers used to
            // call e.preventDefault() + swap to an in-place forgot panel, which
            // orphaned the AI-794 standalone /forgot-password chrome (commit
            // 4ecba69fd0). The 3 anchors now carry real route URLs
            // (route('password.request') + route('login') x2) so natural
            // navigation reaches the polished standalone chrome. Browser
            // Back/Forward + middle-click + shareable URLs + screen-reader
            // semantics all restored. Tab-switching JS for the visible
            // .auth-tab buttons above is preserved (separate UI concern).

            // Alert helper functions
            function showAlert(message, type = 'danger') {
                const alertContainer = document.getElementById('alertContainer');
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';

                let alertHTML = `<div class="alert ${alertClass}">`;

                if (Array.isArray(message)) {
                    alertHTML += '<ul style="margin: 0; padding-left: 20px;">';
                    message.forEach(msg => {
                        alertHTML += `<li>${msg}</li>`;
                    });
                    alertHTML += '</ul>';
                } else if (typeof message === 'object') {
                    alertHTML += '<ul style="margin: 0; padding-left: 20px;">';
                    Object.values(message).forEach(msgs => {
                        if (Array.isArray(msgs)) {
                            msgs.forEach(msg => alertHTML += `<li>${msg}</li>`);
                        } else {
                            alertHTML += `<li>${msgs}</li>`;
                        }
                    });
                    alertHTML += '</ul>';
                } else {
                    alertHTML += message;
                }

                alertHTML += '</div>';
                alertContainer.innerHTML = alertHTML;

                // Scroll to alert
                alertContainer.scrollIntoView({behavior: 'smooth', block: 'nearest'});
            }

            function clearAlerts() {
                const alertContainer = document.getElementById('alertContainer');
                if (alertContainer) {
                    alertContainer.innerHTML = '';
                }
            }

            function setButtonLoading(button, loading = true) {
                if (loading) {
                    button.dataset.originalText = button.innerHTML;
                    button.innerHTML = button.innerHTML + '<span class="spinner"></span>';
                    button.classList.add('loading');
                    button.disabled = true;
                } else {
                    button.innerHTML = button.dataset.originalText || button.innerHTML.replace(/<span class="spinner"><\/span>/, '');
                    button.classList.remove('loading');
                    button.disabled = false;
                }
            }

            // Login Form AJAX
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    clearAlerts();

                    const loginBtn = document.getElementById('loginBtn');
                    setButtonLoading(loginBtn, true);

                    const formData = new FormData(this);

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                        .then(response => response.json())
                        .then(data => {
                            setButtonLoading(loginBtn, false);

                            if (data.error) {
                                showAlert(data.error, 'danger');
                            } else if (data.success || data.data) {
                                showAlert(data.success || 'Login successful! Redirecting...', 'success');

                                // Redirect after success
                                setTimeout(() => {
                                    if (data.redirect) {
                                        window.location.href = data.redirect;
                                    } else if (data.data && data.data.is_admin == 1) {
                                        window.location.href = '{{ admin_url() }}';
                                    } else {
                                        window.location.reload();
                                    }
                                }, 1000);
                            }
                        })
                        .catch(error => {
                            setButtonLoading(loginBtn, false);
                            showAlert('An error occurred. Please try again.', 'danger');
                            console.error('Login error:', error);
                        });
                });
            }

            // Register Form AJAX
            const registerForm = document.getElementById('registerForm');
            if (registerForm) {
                registerForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    clearAlerts();

                    const registerBtn = document.getElementById('registerBtn');
                    setButtonLoading(registerBtn, true);

                    const formData = new FormData(this);

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                        .then(response => response.json())
                        .then(data => {
                            setButtonLoading(registerBtn, false);

                            if (data.error) {
                                showAlert(data.error, 'danger');
                            } else if (data.errors) {
                                showAlert(data.errors, 'danger');
                            } else if (data.success || data.message) {
                                const message = data.success || data.message || 'Registration successful!';
                                showAlert(message, 'success');

                                // Redirect or show login form after success
                                setTimeout(() => {
                                    if (data.redirect) {
                                        window.location.href = data.redirect;
                                    } else {
                                        // Switch to login tab
                                        //document.querySelector('[data-tab="login"]').click();
                                        //showAlert('Account created! Please login.', 'success');

                                        window.location.href = window.location.href;

                                    }
                                }, 2000);
                            }
                        })
                        .catch(error => {
                            setButtonLoading(registerBtn, false);
                            showAlert('An error occurred. Please try again.', 'danger');
                            console.error('Register error:', error);
                        });
                });
            }

            // Forgot Password Form AJAX
            const forgotForm = document.getElementById('forgotForm');
            if (forgotForm) {
                forgotForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    clearAlerts();

                    const forgotBtn = document.getElementById('forgotBtn');
                    setButtonLoading(forgotBtn, true);

                    const formData = new FormData(this);

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                        .then(response => response.json())
                        .then(data => {
                            setButtonLoading(forgotBtn, false);

                            if (data.error) {
                                showAlert(data.error, 'danger');
                            } else if (data.errors) {
                                showAlert(data.errors, 'danger');
                            } else if (data.status || data.success || data.message) {
                                const message = data.status || data.success || data.message;
                                showAlert(message, 'success');

                                // Clear form
                                this.reset();
                            }
                        })
                        .catch(error => {
                            setButtonLoading(forgotBtn, false);
                            showAlert('An error occurred. Please try again.', 'danger');
                            console.error('Forgot password error:', error);
                        });
                });
            }
        });
    </script>

@endsection
