<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | BRACU Medical</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f172a;
            --surface: rgba(30, 41, 59, 0.7);
            --primary: #3b82f6;
            --text-main: #f8fafc;
            --border: rgba(255, 255, 255, 0.1);
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-image: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.15), transparent 40%);
        }
        .login-card {
            background: var(--surface);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 3rem 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        h2 { text-align: center; margin-bottom: 2rem; }
        .input-group { margin-bottom: 1.5rem; }
        .input-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid var(--border);
            color: white;
            border-radius: 0.5rem;
            box-sizing: border-box;
            outline: none;
        }
        .input-group input:focus { border-color: var(--primary); }
        .btn {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn:hover { background: #2563eb; }
        .error { color: #f87171; text-align: center; margin-bottom: 1rem; font-size: 0.9rem; }
        .back { display: block; text-align: center; margin-top: 1.5rem; color: #94a3b8; text-decoration: none; }
        .back:hover { color: white; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Admin Authentication</h2>
        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('admin_login') }}">
            @csrf
            <div class="input-group">
                <input type="email" name="email" placeholder="Admin Email" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn">Secure Login</button>
        </form>
        <a href="{{ route('home') }}" class="back">← Back to Home</a>
    </div>
</body>
</html>
