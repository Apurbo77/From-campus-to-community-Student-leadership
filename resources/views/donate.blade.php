<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate | BRACU Medical</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f172a;
            --surface: rgba(30, 41, 59, 0.7);
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --accent: #f43f5e;
            --success: #10b981;
            --text-main: #f8fafc;
            --text-muted: #cbd5e1;
            --border: rgba(255, 255, 255, 0.1);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); min-height: 100vh; padding: 2rem; background-image: radial-gradient(circle at 50% 10%, rgba(244, 63, 94, 0.15), transparent 40%); }
        .header { display: flex; justify-content: space-between; align-items: center; max-width: 1000px; margin: 0 auto 3rem; }
        .header h1 { font-weight: 800; font-size: 2rem; color: var(--accent); }
        .btn { padding: 0.75rem 1.5rem; background: var(--primary); color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: 0.2s; width: 100%; border: 1px solid transparent; }
        .btn:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); }
        .btn-accent { background: var(--accent); }
        .btn-accent:hover { background: #e11d48; box-shadow: 0 4px 12px rgba(244, 63, 94, 0.4); }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; max-width: 1000px; margin: 0 auto; }
        @media (max-width: 768px) { .grid { grid-template-columns: 1fr; } }
        .card { background: var(--surface); backdrop-filter: blur(12px); border: 1px solid var(--border); border-radius: 1rem; padding: 2rem; }
        .card h2 { font-size: 1.25rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.75rem; color: var(--text-main); }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.875rem; }
        .form-group input, .form-group select { width: 100%; padding: 0.75rem; border-radius: 0.5rem; background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border); color: white; outline: none; }
        .form-group input:focus, .form-group select:focus { border-color: var(--primary); }
        .alert { padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-weight: 600; text-align: center; }
        .alert-success { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); max-width: 1000px; margin: 0 auto 1.5rem; }
    </style>
</head>
<body>
    <div class="header">
        <h1>❤️ Support the Community</h1>
        <a href="{{ route('home') }}" class="btn" style="width: auto;">← Dashboard</a>
    </div>



    <div class="grid">
        <!-- Blood Donation Form -->
        <div class="card" style="border-top: 3px solid var(--accent);">
            <h2>🩸 Record Blood Donation</h2>
            <form method="POST" action="{{ route('donate.blood') }}">
                @csrf
                <div class="form-group">
                    <label>Select Donor</label>
                    <select name="student_id" required>
                        <option value="">Choose your profile...</option>
                        @foreach($students as $s)
                            <option value="{{ $s->student_id }}">{{ $s->full_name }} ({{ $s->blood_group }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Donation Camp</label>
                    <select name="camp_id" required>
                        <option value="">Where did you donate?</option>
                        @foreach($camps as $c)
                            <option value="{{ $c->camp_id }}">{{ $c->title }} ({{ date('M j', strtotime($c->date)) }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Blood Bags Donated</label>
                    <input type="number" name="bag_count" min="1" value="1" required>
                </div>
                
                <div class="form-group">
                    <label>Keep Anonymous?</label>
                    <select name="flag" required>
                        <option value="1">No, show my name on leaderboard</option>
                        <option value="0">Yes, remain anonymous</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-accent">Submit Blood Donation</button>
            </form>
        </div>

        <!-- Financial Donation Form -->
        <div class="card" style="border-top: 3px solid var(--success);">
            <h2>💰 Financial Contribution</h2>
            <form method="POST" action="{{ route('donate.fund') }}">
                @csrf
                <div class="form-group">
                    <label>Select Donor</label>
                    <select name="student_id" required>
                        <option value="">Choose your profile...</option>
                        @foreach($students as $s)
                            <option value="{{ $s->student_id }}">{{ $s->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Donation Amount (৳)</label>
                    <input type="number" name="amount" min="10" step="10" placeholder="e.g., 500" required>
                </div>
                
                <div class="form-group">
                    <label>Keep Anonymous?</label>
                    <select name="flag" required>
                        <option value="1">No, show my name on leaderboard</option>
                        <option value="0">Yes, remain anonymous</option>
                    </select>
                </div>
                
                <button type="submit" class="btn" style="background: var(--success); margin-top: 5.5rem;">Donate Funds</button>
            </form>
        </div>
    </div>
    @include('components.toast')
</body>
</html>
