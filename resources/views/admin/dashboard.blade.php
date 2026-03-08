<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.dashboard') }} - Kibubu Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-gold: #D4AF37;
            --bg-color: #f8f9fa;
            --sidebar-bg: #2c3e50;
            --card-bg: #ffffff;
            --text-color: #212529;
            --sidebar-text: rgba(255,255,255,0.7);
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --bg-color: #121212;
                --sidebar-bg: #000000;
                --card-bg: #1e1e1e;
                --text-color: #f8f9fa;
                --sidebar-text: rgba(255,255,255,0.6);
            }
        }
        body { background-color: var(--bg-color); color: var(--text-color); }
        .sidebar { background: var(--sidebar-bg); min-height: 100vh; color: white; transition: background 0.3s ease; }
        .sidebar .nav-link { color: var(--sidebar-text); }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: white; background: rgba(255,255,255,0.1); }
        .stat-card { border-left: 4px solid var(--primary-gold); background: var(--card-bg); color: var(--text-color); }
        .card { background: var(--card-bg); color: var(--text-color); border: 1px solid rgba(212,175,55,0.1); }
        .table { color: var(--text-color); }
        .table-light { --bs-table-bg: rgba(0,0,0,0.05); --bs-table-color: var(--text-color); }
        #qrcode img { margin: 0 auto; filter: prefers-color-scheme(dark) ? invert(1) : none; }
        @media (prefers-color-scheme: dark) {
            #qrcode { background: white; padding: 10px; border-radius: 10px; display: inline-block; }
            .form-control, .form-select { background-color: #2b2b2b; color: #fff; border-color: #444; }
            .form-control:focus { background-color: #2b2b2b; color: #fff; }
            .nav-tabs .nav-link { color: #aaa; }
            .nav-tabs .nav-link.active { background-color: var(--card-bg); color: #fff; border-color: #444 #444 var(--card-bg); }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar p-3">
            <h4 class="mb-4">Kibubu Admin</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#"><i class="fas fa-chart-line me-2"></i> {{ __('messages.dashboard') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" id="sidebar-settings-link"><i class="fas fa-cog me-2"></i> {{ __('messages.settings') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}" target="_blank"><i class="fas fa-external-link-alt me-2"></i> {{ __('messages.view_site') }}</a>
                </li>
                <li class="nav-item mt-4">
                    <a class="nav-link text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> {{ __('messages.logout') }}
                    </a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">{{ __('messages.dashboard') }}</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <form action="{{ route('logout') }}" method="POST" class="me-2">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt me-1"></i> {{ __('messages.logout') }}</button>
                    </form>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> PDF
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button"><i class="fas fa-home me-1"></i> {{ __('messages.overview') }}</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button"><i class="fas fa-cog me-1"></i> {{ __('messages.branding') }}</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="smtp-tab" data-bs-toggle="tab" data-bs-target="#smtp" type="button"><i class="fas fa-envelope me-1"></i> {{ __('messages.email_smtp') }}</button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview">
                    <!-- Stats Boxes -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card stat-card shadow-sm p-3">
                                <div class="text-muted small">{{ __('messages.total_clicks') }}</div>
                                <div class="h3 fw-bold">{{ $totalIntents }}</div>
                            </div>
                        </div>
                        @foreach($providerStats as $stat)
                        <div class="col-md-2">
                            <div class="card shadow-sm p-3 text-center">
                                <div class="text-muted small">{{ $stat->provider_name }}</div>
                                <div class="h4 fw-bold">{{ $stat->total }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <!-- Click History -->
                        <div class="col-md-8">
                            <div class="card shadow-sm">
                                <div class="card-header bg-white fw-bold">{{ __('messages.recent_logs') }}</div>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ __('messages.provider') }}</th>
                                                <th>{{ __('messages.device') }}</th>
                                                <th>{{ __('messages.ip_address') }}</th>
                                                <th>{{ __('messages.time') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($intents as $intent)
                                            <tr>
                                                <td><span class="badge bg-info text-dark">{{ $intent->provider_name }}</span></td>
                                                <td><i class="fas fa-{{ $intent->device_type == 'mobile' ? 'mobile-alt' : ($intent->device_type == 'tablet' ? 'tablet-alt' : 'desktop') }} me-2"></i>{{ ucfirst($intent->device_type) }}</td>
                                                <td><code>{{ $intent->ip_address }}</code></td>
                                                <td>{{ $intent->created_at->diffForHumans() }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer bg-white">
                                    {{ $intents->links() }}
                                </div>
                            </div>
                        </div>

                        <!-- QR Code Generator -->
                        <div class="col-md-4">
                            <div class="card shadow-sm text-center p-4">
                                <h5 class="mb-3">{{ __('messages.site_qr') }}</h5>
                                <p class="small text-muted">{{ __('messages.scan_qr') }}</p>
                                <div id="qrcode" class="mb-3 d-flex justify-content-center"></div>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-primary" onclick="downloadQR()">
                                        <i class="fas fa-download me-1"></i> {{ __('messages.download_qr') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Branding Settings Tab -->
                <div class="tab-pane fade" id="settings">
                    <div class="card shadow-sm col-md-6">
                        <div class="card-body">
                            <h5 class="card-title mb-4">{{ __('messages.branding_config') }}</h5>
                            <form action="{{ route('admin.settings.save') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.primary_gold') }}</label>
                                    <input type="color" name="primary_color" class="form-control form-control-color w-100" value="{{ $allSettings['primary_color'] ?? '#D4AF37' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.secondary_gold') }}</label>
                                    <input type="color" name="secondary_color" class="form-control form-control-color w-100" value="{{ $allSettings['secondary_color'] ?? '#FFD700' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.charity_red') }}</label>
                                    <input type="color" name="charity_red" class="form-control form-control-color w-100" value="{{ $allSettings['charity_red'] ?? '#B22222' }}">
                                </div>
                                <button type="submit" class="btn btn-primary">{{ __('messages.save_branding') }}</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- SMTP Settings Tab -->
                <div class="tab-pane fade" id="smtp">
                    <div class="card shadow-sm col-md-8">
                        <div class="card-body">
                            <h5 class="card-title mb-4">{{ __('messages.email_config') }}</h5>
                            <form action="{{ route('admin.settings.save') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('messages.admin_email') }}</label>
                                        <input type="email" name="admin_email" class="form-control" value="{{ $allSettings['admin_email'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('messages.report_schedule') }}</label>
                                        <input type="time" name="report_schedule" class="form-control" value="{{ $allSettings['report_schedule'] ?? '08:00' }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-9 mb-3">
                                        <label class="form-label">{{ __('messages.smtp_host') }}</label>
                                        <input type="text" name="mail_host" class="form-control" value="{{ $allSettings['mail_host'] ?? '' }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">{{ __('messages.smtp_port') }}</label>
                                        <input type="number" name="mail_port" class="form-control" value="{{ $allSettings['mail_port'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('messages.smtp_username') }}</label>
                                        <input type="text" name="mail_username" class="form-control" value="{{ $allSettings['mail_username'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('messages.smtp_password') }}</label>
                                        <input type="password" name="mail_password" class="form-control" value="{{ $allSettings['mail_password'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('messages.encryption') }}</label>
                                        <select name="mail_encryption" class="form-select">
                                            <option value="null" {{ ($allSettings['mail_encryption'] ?? '') == 'null' ? 'selected' : '' }}>None</option>
                                            <option value="tls" {{ ($allSettings['mail_encryption'] ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ ($allSettings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('messages.from_address') }}</label>
                                        <input type="email" name="mail_from_address" class="form-control" value="{{ $allSettings['mail_from_address'] ?? '' }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('messages.from_name') }}</label>
                                        <input type="text" name="mail_from_name" class="form-control" value="{{ $allSettings['mail_from_name'] ?? '' }}">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">{{ __('messages.save_smtp') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const qrContainer = document.getElementById("qrcode");
    const siteUrl = window.location.origin;
    
    new QRCode(qrContainer, {
        text: siteUrl,
        width: 180,
        height: 180,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });

    function downloadQR() {
        const img = qrContainer.querySelector('img');
        if (img) {
            const link = document.createElement('a');
            link.href = img.src;
            link.download = 'kibubu-qr-code.png';
            link.click();
        }
    }

    // Sidebar link to activate settings tab
    document.getElementById('sidebar-settings-link').addEventListener('click', function(e) {
        e.preventDefault();
        const settingsTab = new bootstrap.Tab(document.getElementById('settings-tab'));
        settingsTab.show();
    });
</script>
</body>
</html>
