<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $allSettings['site_name'] ?? 'Kibubu Changu na Dorcas' }} - Charity Drive</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="{{ $allSettings['primary_color'] ?? '#D4AF37' }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        :root {
            --primary-gold: {{ $allSettings['primary_color'] ?? '#D4AF37' }};
            --secondary-gold: {{ $allSettings['secondary_color'] ?? '#FFD700' }};
            --charity-red: {{ $allSettings['charity_red'] ?? '#B22222' }};
            --bg-color: #ffffff;
            --text-color: #212529;
            --card-bg: #ffffff;
            --section-title-color: var(--primary-gold);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-color: #121212;
                --text-color: #f8f9fa;
                --card-bg: #1e1e1e;
                --section-title-color: var(--secondary-gold);
            }
        }

        body {
            background-color: var(--bg-color) !important;
            color: var(--text-color) !important;
        }
        .payment-card {
            background-color: var(--card-bg) !important;
            border-color: rgba(212, 175, 55, 0.2) !important;
            color: var(--text-color) !important;
        }
        .section-title {
            color: var(--section-title-color) !important;
        }
        .text-muted {
            color: rgba(255,255,255,0.6) !important;
        }
        @media (prefers-color-scheme: light) {
            .text-muted { color: #6c757d !important; }
        }
    </style>
</head>
<body>

    <section class="hero-section text-center">
        <div class="container d-flex justify-content-between align-items-center mb-3">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-light dropdown-toggle border-0" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-globe me-1"></i> {{ strtoupper(app()->getLocale()) }}
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'sw') }}">Kiswahili</a></li>
                </ul>
            </div>
            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light border-0 opacity-75"><i class="fas fa-lock me-1"></i> {{ __('messages.admin') }}</a>
        </div>
        <div class="container">
            <h1 class="display-5 fw-bold mb-3">{{ $allSettings['hero_title_' . app()->getLocale()] ?? __('messages.hero_title') }}</h1>
            <p class="slogan mb-4">{{ $allSettings['slogan_' . app()->getLocale()] ?? __('messages.slogan') }}</p>
            <p class="lead px-4">{{ $allSettings['hero_lead_' . app()->getLocale()] ?? __('messages.hero_lead') }}</p>
        </div>
    </section>

    <div class="container my-5">
        <!-- Mobile Money Section -->
        <div class="mb-5">
            <h2 class="section-title">{{ $allSettings['mobile_money_title_' . app()->getLocale()] ?? __('messages.mobile_money') }}</h2>
            <div class="row g-3">
                @foreach($mobileProviders as $provider)
                <div class="col-6 col-md-3">
                    <div class="card payment-card h-100 p-3 text-center" onclick="handleMobilePayment('{{ $provider->name }}', '{{ $provider->ussd_string }}')">
                        <div class="fw-bold mb-2">{{ $provider->name }}</div>
                        <div class="small text-muted mb-3">{{ $provider->account_number }}</div>
                        <a href="tel:{{ $provider->ussd_string }}" class="btn btn-ussd mt-auto">{{ __('messages.pay_via_ussd') }}</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Bank Section -->
        <div>
            <h2 class="section-title">{{ $allSettings['bank_title_' . app()->getLocale()] ?? __('messages.bank_transfer') }}</h2>
            <div class="row g-3">
                @foreach($bankProviders as $provider)
                <div class="col-12 col-md-4">
                    <div class="card payment-card p-3 position-relative" onclick="copyToClipboard('{{ $provider->account_number }}', '{{ $provider->name }}')">
                        <span class="copy-badge"><i class="fas fa-copy me-1"></i> {{ __('messages.tap_to_copy') }}</span>
                        <div class="fw-bold h5 mb-1">{{ $provider->name }}</div>
                        <div class="bank-details">
                            <span class="text-muted">{{ __('messages.account_number') }}:</span>
                            <div class="h4 fw-bold mt-1">{{ $provider->account_number }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <footer class="text-center py-4 text-muted border-top">
        <p class="mb-0">&copy; {{ date('Y') }} {{ $allSettings['site_name'] ?? 'Kibubu Digital' }}. {{ $allSettings['footer_text_' . app()->getLocale()] ?? __('messages.footer_rights') }}</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function getDeviceType() {
            const ua = navigator.userAgent;
            if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
                return "tablet";
            }
            if (/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/i.test(ua)) {
                return "mobile";
            }
            return "desktop";
        }

        function logIntent(providerName) {
            $.ajax({
                url: "{{ route('log.intent') }}",
                method: "POST",
                data: {
                    provider_name: providerName,
                    device_type: getDeviceType(),
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log('Intent logged successfully');
                }
            });
        }

        function handleMobilePayment(name, ussd) {
            logIntent(name);
            // The link is already a tel: link, so it will trigger on click if not intercepted.
            // But we call logIntent first.
        }

        function copyToClipboard(text, name) {
            logIntent(name);
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);

            Swal.fire({
                icon: 'success',
                title: "{{ __('messages.copied') }}",
                text: "{{ __('messages.copied_text', ['name' => ':name', 'number' => ':number']) }}".replace(':name', name).replace(':number', text),
                timer: 2000,
                showConfirmButton: false,
                background: getDeviceTheme() === 'dark' ? '#1e1e1e' : '#FFFDF5',
                color: getDeviceTheme() === 'dark' ? '#f8f9fa' : '#996515',
                iconColor: 'var(--primary-gold)'
            });
        }

        function getDeviceTheme() {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js').then(registration => {
                    // Check for updates to the service worker immediately
                    registration.update();
                }).catch(error => {
                    console.log('ServiceWorker registration failed: ', error);
                });
            });
        }
    </script>
</body>
</html>
