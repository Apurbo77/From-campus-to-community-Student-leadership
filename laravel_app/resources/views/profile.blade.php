<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Dashboard | BRACU Medical</title>
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
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); min-height: 100vh; padding: 2rem; background-image: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.15), transparent 60%); }
        .container { max-width: 800px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .card { background: var(--surface); backdrop-filter: blur(12px); border: 1px solid var(--border); border-radius: 1rem; padding: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.3); text-align: center; }
        
        .avatar-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            font-weight: 800;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }

        .user-name { font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem; }
        .user-email { color: var(--text-muted); font-size: 1rem; margin-bottom: 2rem; }

        .btn-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 600px) {
            .btn-grid { grid-template-columns: 1fr; }
        }

        .btn-large {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 1rem;
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-large:hover {
            background: rgba(59, 130, 246, 0.1);
            border-color: var(--primary);
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-large.edit-btn:hover {
            background: rgba(244, 63, 94, 0.1);
            border-color: var(--accent);
        }

        .btn-icon { font-size: 2.5rem; }

        .back-btn { display: inline-block; padding: 0.5rem 1rem; background: rgba(255,255,255,0.1); color: white; border-radius: 0.5rem; text-decoration: none; transition: 0.2s; }
        .back-btn:hover { background: rgba(255,255,255,0.2); }
    </style>
</head>
<body>
    @include('components.toast')

    <div class="container">
        <div class="header">
            <h1 style="font-weight: 800; font-size: 1.5rem;">My Dashboard</h1>
            <a href="{{ route('home') }}" class="back-btn">← Home</a>
        </div>

        <div class="card">
            <div class="avatar-circle">
                {{ strtoupper(substr($student->full_name, 0, 1)) }}
            </div>
            
            <h2 class="user-name">{{ $student->full_name }}</h2>
            <p class="user-email">{{ $student->gsuit }} • {{ $student->blood_group }}</p>

            <div class="btn-grid">
                <a href="{{ route('profile.edit') }}" class="btn-large edit-btn">
                    <span class="btn-icon">⚙️</span>
                    Edit Profile Settings
                </a>
                
                <a href="{{ route('training') }}" class="btn-large">
                    <span class="btn-icon">🏥</span>
                    Training & Donation Hub
                </a>
            </div>
        </div>
    </div>
</body>
</html>
