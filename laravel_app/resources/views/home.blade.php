<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRACU Medical Students | From Campus to Community</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f172a;
            --surface: rgba(30, 41, 59, 0.7);
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --accent: #f43f5e;
            --text-main: #f8fafc;
            --text-muted: #cbd5e1;
            --border: rgba(255, 255, 255, 0.1);
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(59, 130, 246, 0.15), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(244, 63, 94, 0.15), transparent 25%);
            background-attachment: fixed;
            min-height: 100vh;
            line-height: 1.6;
        }

        .navbar {
            backdrop-filter: blur(12px);
            background: rgba(15, 23, 42, 0.8);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand { font-weight: 800; font-size: 1.25rem; background: linear-gradient(to right, #3b82f6, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        .navbar-links a {
            color: var(--text-muted);
            text-decoration: none;
            margin-left: 1.5rem;
            font-weight: 600;
            transition: color 0.2s ease;
        }
        .navbar-links a:hover { color: var(--text-main); }

        .hero {
            text-align: center;
            padding: 5rem 1rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .hero h1 { font-size: 3.5rem; font-weight: 800; margin-bottom: 1rem; line-height: 1.1; }
        .hero p { font-size: 1.25rem; color: var(--text-muted); margin-bottom: 2rem; }

        .search-container {
            background: var(--surface);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            padding: 1.5rem;
            border-radius: 1rem;
            margin-bottom: 4rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .search-form {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .input-field {
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid var(--border);
            color: var(--text-main);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            outline: none;
            flex: 1;
            min-width: 200px;
            transition: border-color 0.2s;
        }
        .input-field:focus { border-color: var(--primary); }
        .input-field option { background: var(--bg); color: var(--text-main); }

        .btn {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s, background 0.2s, box-shadow 0.2s;
        }
        .btn:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); }
        .btn-outline { background: transparent; border: 1px solid var(--border); }
        .btn-outline:hover { background: rgba(255,255,255,0.05); }

        .grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem 4rem;
        }

        @media (max-width: 768px) {
            .grid { grid-template-columns: 1fr; }
            .navbar-links { display: none; }
        }

        .glass-card {
            background: var(--surface);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            transition: transform 0.3s;
        }
        .glass-card:hover { transform: translateY(-4px); }

        .glass-card h3 { 
            font-size: 1.25rem; 
            margin-bottom: 1.25rem; 
            border-bottom: 1px solid var(--border);
            padding-bottom: 0.5rem;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stat-highlight {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
            margin: 0.5rem 0;
        }

        .list-item {
            padding: 1rem;
            border-radius: 0.5rem;
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.05);
            margin-bottom: 0.75rem;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 999px;
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            font-weight: 600;
        }
        
        .badge-red { background: rgba(244, 63, 94, 0.2); color: #fb7185; }

        .action-flex { display: flex; flex-direction: column; gap: 1rem; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="navbar-brand">BRACU Medical</div>
        <div class="navbar-links">
            <a href="{{ route('training') }}">Training & Donation</a>
            <a href="{{ route('blood_matching') }}">Blood Matching</a>
            <a href="{{ route('leaderboard') }}">Leaderboard</a>
            
            @if(session('student_id'))
                <a href="{{ route('training') }}" style="color: var(--primary);">{{ session('student_name', 'My Profile') }}</a>
                <a href="{{ route('logout') }}" style="color: #fb7185;">Logout</a>
            @else
                <a href="{{ route('login') }}" style="color: white; border-bottom: 2px solid var(--primary);">Student Login</a>
            @endif

            <a href="{{ route('admin_login') }}" class="btn btn-outline" style="margin-left: 1rem; border-radius: 999px; padding: 0.5rem 1.25rem;">Admin</a>
        </div>
    </nav>

    <header class="hero">
        <h1>From Campus to Community</h1>
        <p>A unified platform for BRACU students to volunteer, donate, and save lives.</p>

        <div class="search-container">
            <form method="GET" action="{{ route('home') }}" class="search-form">
                <input type="text" name="area" value="{{ request('area') }}" placeholder="Search by area (e.g., Dhanmondi)" class="input-field">
                <select name="blood_group" class="input-field" style="flex: 0 0 auto;">
                    <option value="">Any blood group</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="search" value="1">
                <button type="submit" class="btn">Find Volunteers</button>
            </form>
        </div>
    </header>

    <div class="grid">
        
        <!-- Main Content Column -->
        <div>
            @if(request('search'))
            <div class="glass-card" style="border: 1px solid var(--primary);">
                <h3>🔍 Search Results</h3>
                @if($students->isEmpty())
                    <p style="color: var(--text-muted);">No volunteers found in this area with the specified blood group.</p>
                @else
                    @foreach($students as $student)
                    <div class="list-item" style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="font-size: 1.1rem;">{{ $student->full_name }}</strong>
                            <div style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">
                                📍 {{ $student->area }}{{ $student->city ? ', ' . $student->city : '' }}
                                <br>📞 {{ $student->phone }}
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <span class="badge badge-red" style="font-size: 0.9rem;">{{ $student->blood_group }}</span>
                            <br><span style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px; display: inline-block;">{{ $student->available }}</span>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
            @endif

            <div class="glass-card">
                <h3>🩸 Live Blood Donation Camps</h3>
                @forelse($camps as $camp)
                    <div class="list-item">
                        <strong>{{ $camp->title }}</strong>
                        <div style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">
                            📍 {{ $camp->location }} | 📅 {{ $camp->date ?? 'TBA' }} @if($camp->time) 🕒 {{ $camp->time }} @endif
                        </div>
                    </div>
                @empty
                    <p style="color: var(--text-muted);">No active camps currently.</p>
                @endforelse
            </div>

            <div class="glass-card">
                <h3>📚 Recent Training Sessions</h3>
                @foreach($recentSessions as $session)
                    <div class="list-item">
                        <strong>{{ $session->title }}</strong>
                        <div style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">
                            📍 {{ $session->location }} | 📅 {{ $session->date ?? 'TBA' }}
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <!-- Sidebar Column -->
        <div>
            <div class="glass-card">
                <h3>⚡ Quick Actions</h3>
                <div class="action-flex">
                    <a href="{{ route('training') }}" class="btn" style="text-align: center;">Sign up for Training</a>
                    <a href="{{ route('donate') }}" class="btn" style="text-align: center; background: var(--accent);">Donate Blood or Fund</a>
                    <a href="{{ route('certification') }}" class="btn btn-outline" style="text-align: center;">Apply for Certification</a>
                </div>
            </div>

            <div class="glass-card" style="text-align: center;">
                <h3>❤️ Impact Metrics</h3>
                <div>
                    <p style="color: var(--text-muted); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 1px;">Total Funds Raised</p>
                    <div class="stat-highlight">৳{{ number_format($totalDonations, 2) }}</div>
                    <p style="font-size: 0.875rem; color: var(--text-muted);">Latest: <strong>{{ $latestDonation ?? 'None' }}</strong></p>
                </div>
                
                <hr style="border: 0; border-top: 1px solid var(--border); margin: 1.5rem 0;">
                
                <div>
                    <p style="color: var(--text-muted); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 1px;">Blood Bags Donated</p>
                    <div class="stat-highlight" style="color: var(--accent);">{{ number_format($totalBags) }}</div>
                    @if($latestBlood)
                    <p style="font-size: 0.8rem; color: var(--text-muted); line-height: 1.4;">
                        Latest: <strong>{{ $latestBlood->student_name }}</strong> donated {{ $latestBlood->bag_count }} bag(s) at {{ $latestBlood->camp_name }}
                        ({{ date('M j, Y', strtotime($latestBlood->donation_date)) }})
                    </p>
                    @endif
                </div>
            </div>

            <div class="glass-card">
                <h3>⭐ Top Volunteers</h3>
                @foreach($topVolunteers as $tv)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--border);' : '' }}">
                        <span style="font-weight: 600;">{{ $tv->full_name }}</span>
                        <span class="badge">★ {{ number_format($tv->avg_rating, 1) }}</span>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
    
</body>
</html>
