<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; }
        .container { padding: 20px; }
        .header { background: #D4AF37; color: white; padding: 10px; text-align: center; }
        .stat-box { border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; }
        .total { font-size: 24px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Daily Performance Report</h1>
        </div>
        <p>Hello Admin,</p>
        <p>Here is the performance report for Kibubu Digital for the last 24 hours.</p>
        
        <div class="stat-box">
            <div class="total">Total Clicks: {{ $totalClicks }}</div>
        </div>

        <h3>Provider Breakdown:</h3>
        <ul>
            @foreach($stats as $stat)
                <li><strong>{{ $stat->provider_name }}:</strong> {{ $stat->total }} clicks</li>
            @endforeach
        </ul>

        <p>Thank you for your commitment to the charity drive!</p>
    </div>
</body>
</html>
