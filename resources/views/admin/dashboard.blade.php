<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.dashboard') }} - {{ $allSettings['site_name'] ?? 'Kibubu Digital' }}</title>
    <link rel="icon" type="image/png" href="{{ isset($allSettings['site_logo']) ? asset($allSettings['site_logo']) : asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($allSettings['site_logo']) ? asset($allSettings['site_logo']) : asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
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
            <h4 class="mb-4">{{ $allSettings['site_name'] ?? 'Kibubu Admin' }}</h4>
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
                    <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button"><i class="fas fa-palette me-1"></i> {{ __('messages.branding') }}</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="content-tab" data-bs-toggle="tab" data-bs-target="#content" type="button"><i class="fas fa-edit me-1"></i> Content</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="providers-tab" data-bs-toggle="tab" data-bs-target="#providers" type="button"><i class="fas fa-list me-1"></i> Providers</button>
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
                                    <table id="intents-table" class="table table-hover mb-0 w-100">
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
                            </div>
                        </div>

                        <!-- QR Code Generator -->
                        <div class="col-md-4">
                            <div class="card shadow-sm text-center p-4">
                                <h5 class="mb-3">{{ __('messages.site_qr') }}</h5>
                                <p class="small text-muted mb-2">{{ __('messages.scan_qr') }}</p>
                                
                                <div class="input-group input-group-sm mb-3">
                                    <input type="text" id="qr-target-url" class="form-control" value="{{ config('app.url') }}" placeholder="Target URL">
                                    <button class="btn btn-outline-secondary" type="button" onclick="generateQR()" title="Regenerate QR Code">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>

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

                <div class="tab-pane fade" id="settings">
                    <div class="card shadow-sm col-md-6">
                        <div class="card-body">
                            <h5 class="card-title mb-4">{{ __('messages.branding_config') }}</h5>
                            <form action="{{ route('admin.settings.save') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4 text-center">
                                    <label class="form-label d-block text-start">Application Logo</label>
                                    <div class="mb-3">
                                        <img src="{{ isset($allSettings['site_logo']) ? asset($allSettings['site_logo']) : asset('images/logo.png') }}" 
                                             alt="Logo" class="img-thumbnail" style="max-height: 100px; background-color: #f8f9fa;">
                                    </div>
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                    <div class="form-text text-start">Upload a high-quality PNG or JPG. Square logos work best for icons.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Site Name</label>
                                    <input type="text" name="site_name" class="form-control" value="{{ $allSettings['site_name'] ?? 'Kibubu Digital' }}">
                                </div>
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

                <!-- Content Management Tab -->
                <div class="tab-pane fade" id="content">
                    <div class="card shadow-sm col-md-10">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Landing Page Content</h5>
                            <form action="{{ route('admin.settings.save') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>English (EN)</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Hero Title</label>
                                            <input type="text" name="hero_title_en" class="form-control" value="{{ $allSettings['hero_title_en'] ?? '' }}" placeholder="{{ __('messages.hero_title') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Slogan</label>
                                            <input type="text" name="slogan_en" class="form-control" value="{{ $allSettings['slogan_en'] ?? '' }}" placeholder="{{ __('messages.slogan') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Hero Lead</label>
                                            <textarea name="hero_lead_en" class="form-control" rows="2" placeholder="{{ __('messages.hero_lead') }}">{{ $allSettings['hero_lead_en'] ?? '' }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Footer Text</label>
                                            <input type="text" name="footer_text_en" class="form-control" value="{{ $allSettings['footer_text_en'] ?? '' }}" placeholder="Kibubu Digital. All Rights Reserved.">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Kiswahili (SW)</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Hero Title (SW)</label>
                                            <input type="text" name="hero_title_sw" class="form-control" value="{{ $allSettings['hero_title_sw'] ?? '' }}" placeholder="Kibubu Changu na Dorcas">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Slogan (SW)</label>
                                            <input type="text" name="slogan_sw" class="form-control" value="{{ $allSettings['slogan_sw'] ?? '' }}" placeholder="Kidogo changu, makazi yao!">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Hero Lead (SW)</label>
                                            <textarea name="hero_lead_sw" class="form-control" rows="2" placeholder="Mchango wako mdogo unajenga makazi kwa wahitaji.">{{ $allSettings['hero_lead_sw'] ?? '' }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Footer Text (SW)</label>
                                            <input type="text" name="footer_text_sw" class="form-control" value="{{ $allSettings['footer_text_sw'] ?? '' }}" placeholder="Haki zote zimehifadhiwa.">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Mobile Money Section Title (EN)</label>
                                            <input type="text" name="mobile_money_title_en" class="form-control" value="{{ $allSettings['mobile_money_title_en'] ?? '' }}" placeholder="Mobile Money">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Bank Section Title (EN)</label>
                                            <input type="text" name="bank_title_en" class="form-control" value="{{ $allSettings['bank_title_en'] ?? '' }}" placeholder="Bank Transfer">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Mobile Money Section Title (SW)</label>
                                            <input type="text" name="mobile_money_title_sw" class="form-control" value="{{ $allSettings['mobile_money_title_sw'] ?? '' }}" placeholder="Pesa ya Mtandao">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Bank Section Title (SW)</label>
                                            <input type="text" name="bank_title_sw" class="form-control" value="{{ $allSettings['bank_title_sw'] ?? '' }}" placeholder="Benki">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Content Changes</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Provider Management Tab -->
                <div class="tab-pane fade" id="providers">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Payment Providers</h5>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addProviderModal">
                                <i class="fas fa-plus me-1"></i> Add Provider
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="providers-table" class="table table-hover mb-0 w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Account/USSD</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allProviders = \App\Models\PaymentProvider::all();
                                    @endphp
                                    @foreach($allProviders as $p)
                                    <tr>
                                        <td>{{ $p->name }}</td>
                                        <td><span class="badge bg-secondary">{{ strtoupper($p->type) }}</span></td>
                                        <td><code>{{ $p->ussd_string ?? $p->account_number }}</code></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editProvider({{ json_encode($p) }})"><i class="fas fa-edit"></i></button>
                                            <form action="{{ route('admin.providers.delete', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this provider?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

<!-- Add/Edit Provider Modal -->
<div class="modal fade" id="addProviderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Payment Provider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="providerForm" action="{{ route('admin.providers.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="methodField" value="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Provider Name</label>
                        <input type="text" name="name" id="p_name" class="form-control" required placeholder="e.g. M-PESA, CRDB">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" id="p_type" class="form-select" required onchange="toggleFields()">
                            <option value="mobile_money">Mobile Money</option>
                            <option value="bank">Bank</option>
                        </select>
                    </div>
                    <div id="mobileFields">
                        <div class="mb-3">
                            <label class="form-label">USSD String</label>
                            <input type="text" name="ussd_string" id="p_ussd" class="form-control" placeholder="*150*00#">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Number</label>
                        <input type="text" name="account_number" id="p_account" class="form-control" required placeholder="12345678">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save Provider</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
    const qrContainer = document.getElementById("qrcode");

    function generateQR() {
        qrContainer.innerHTML = ""; // Clear existing QR code
        const urlInput = document.getElementById("qr-target-url");
        const targetUrl = urlInput && urlInput.value ? urlInput.value : window.location.origin;
        
        new QRCode(qrContainer, {
            text: targetUrl,
            width: 180,
            height: 180,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    }

    // Initialize QR code on load
    generateQR();

    function downloadQR() {
        const canvas = qrContainer.querySelector('canvas');
        if (canvas) {
            const link = document.createElement('a');
            link.href = canvas.toDataURL("image/png");
            link.download = 'kibubu-qr-code.png';
            link.click();
        } else {
            const img = qrContainer.querySelector('img');
            if (img && img.src) {
                const link = document.createElement('a');
                link.href = img.src;
                link.download = 'kibubu-qr-code.png';
                link.click();
            }
        }
    }

    // Sidebar link to activate settings tab
    document.getElementById('sidebar-settings-link').addEventListener('click', function(e) {
        e.preventDefault();
        const settingsTab = new bootstrap.Tab(document.getElementById('settings-tab'));
        settingsTab.show();
    });

    function toggleFields() {
        const type = document.getElementById('p_type').value;
        document.getElementById('mobileFields').style.display = type === 'mobile_money' ? 'block' : 'none';
        document.getElementById('p_ussd').required = type === 'mobile_money';
    }

    function editProvider(p) {
        document.getElementById('modalTitle').innerText = 'Edit Provider';
        let updateUrl = "{{ route('admin.providers.update', ':id') }}".replace(':id', p.id);
        document.getElementById('providerForm').action = updateUrl;
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('p_name').value = p.name;
        document.getElementById('p_type').value = p.type;
        document.getElementById('p_ussd').value = p.ussd_string || '';
        document.getElementById('p_account').value = p.account_number;
        document.getElementById('saveBtn').innerText = 'Update Provider';
        toggleFields();
        new bootstrap.Modal(document.getElementById('addProviderModal')).show();
    }

    // Reset modal on close
    document.getElementById('addProviderModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('modalTitle').innerText = 'Add Payment Provider';
        document.getElementById('providerForm').action = "{{ route('admin.providers.store') }}";
        document.getElementById('methodField').value = 'POST';
        document.getElementById('providerForm').reset();
        document.getElementById('saveBtn').innerText = 'Save Provider';
        toggleFields();
    });

    // Restore active tab from localStorage if available
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize DataTables
        const intentsTable = $('#intents-table').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[3, 'desc']],
            language: {
                search: "{{ __('messages.search') }}:",
                lengthMenu: "_MENU_ per page",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: { previous: "‹", next: "›" }
            },
            columnDefs: [{ targets: [0, 1, 2], responsivePriority: 1 }, { targets: 3, responsivePriority: 2 }]
        });

        const providersTable = $('#providers-table').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'asc']],
            language: {
                search: "{{ __('messages.search') }}:",
                lengthMenu: "_MENU_ per page",
                emptyTable: "No providers added yet.",
            },
            columnDefs: [{ targets: -1, orderable: false, searchable: false }]
        });

        // Re-draw DataTables when their tab is shown (fixes column width glitch in hidden tabs)
        document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (event) {
                const target = event.target.getAttribute('data-bs-target');
                localStorage.setItem('kibubu_admin_active_tab', target);
                if (target === '#overview') intentsTable.columns.adjust().responsive.recalc();
                if (target === '#providers') providersTable.columns.adjust().responsive.recalc();
            });
        });

        const activeTab = localStorage.getItem('kibubu_admin_active_tab');
        if (activeTab) {
            const tabButton = document.querySelector(`button[data-bs-target="${activeTab}"]`);
            if (tabButton) {
                const tabInstance = new bootstrap.Tab(tabButton);
                tabInstance.show();
            }
        }
    });
</script>
</body>
</html>
