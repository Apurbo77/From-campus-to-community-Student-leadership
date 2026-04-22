<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login | BRACU Medical</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
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
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); min-height: 100vh; display: flex; align-items: center; justify-content: center; background-image: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.15), transparent 60%); padding: 2rem; }
        .card { background: var(--surface); backdrop-filter: blur(12px); border: 1px solid var(--border); border-radius: 1rem; padding: 3rem; width: 100%; max-width: 450px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .card h1 { font-size: 1.75rem; margin-bottom: 2rem; color: var(--text-main); text-align: center; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.875rem; }
        .form-group input { width: 100%; padding: 0.75rem; border-radius: 0.5rem; background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border); color: white; outline: none; }
        .form-group input:focus { border-color: var(--primary); }
        .btn { width: 100%; padding: 0.75rem; background: var(--primary); color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: 0.2s; margin-top: 1rem; }
        .btn:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
        .back { display: block; text-align: center; margin-top: 2rem; color: var(--text-muted); text-decoration: none; }
        .back:hover { color: white; }
    </style>
</head>
<body>
    @include('components.toast')

    <div class="card">
        <h1>🎓 Student Login</h1>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label>G-Suite Address</label>
                <input type="email" name="gsuit" required placeholder="name@bracu.ac.bd">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            
            <button type="submit" class="btn">Log In</button>
        </form>
        
        <a href="{{ route('home') }}" class="back">← Return to Dashboard</a>
    </div>
</body>
</html>
