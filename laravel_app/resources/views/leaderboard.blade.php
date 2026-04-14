<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboards | BRACU Medical</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f172a;
            --surface: rgba(30, 41, 59, 0.7);
            --primary: #3b82f6;
            --accent: #f43f5e;
            --text-main: #f8fafc;
            --text-muted: #cbd5e1;
            --border: rgba(255, 255, 255, 0.1);
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text-main);
            margin: 0;
            padding: 2rem;
            min-height: 100vh;
        }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; }
        .header h1 { font-weight: 800; font-size: 2rem; }
        .btn { padding: 0.5rem 1rem; background: var(--primary); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
        @media (max-width: 768px) { .grid { grid-template-columns: 1fr; } }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 1rem; padding: 1.5rem; }
        .card h2 { border-bottom: 1px solid var(--border); padding-bottom: 1rem; margin-bottom: 1rem; color: var(--accent); }
        .row { display: flex; justify-content: space-between; padding: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .row:last-child { border-bottom: none; }
        .rank-1 { color: #fbbf24; font-weight: bold; }
        .rank-2 { color: #94a3b8; font-weight: bold; }
        .rank-3 { color: #b45309; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header" style="max-width: 1200px; margin: 0 auto 3rem;">
        <h1>🏆 BRACU Impact Leaderboards</h1>
        <a href="{{ route('home') }}" class="btn">← Back to Home</a>
    </div>

    <div class="grid" style="max-width: 1200px; margin: 0 auto;">
        <div class="card">
            <h2>💰 Financial Contributions</h2>
            @foreach($leaders as $index => $leader)
                <div class="row">
                    <div style="display: flex; gap: 1rem;">
                        <span class="rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
                        <span>{{ $leader->flag == 0 ? 'Anonymous' : ($leader->full_name ?? $leader->name) }}</span>
                    </div>
                    <span style="font-weight: 600;">৳{{ number_format($leader->finance_score) }}</span>
                </div>
            @endforeach
        </div>

        <div class="card">
            <h2>🩸 Blood Donations</h2>
            @foreach($blood as $index => $b)
                <div class="row">
                    <div style="display: flex; gap: 1rem;">
                        <span class="rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
                        <span>{{ $b->flag == 0 ? 'Anonymous' : ($b->full_name ?? $b->name) }}</span>
                    </div>
                    <span style="font-weight: 600; color: var(--accent);">{{ $b->blood_score }} points</span>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
