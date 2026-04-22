<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certification | BRACU Medical</title>
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
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); min-height: 100vh; padding: 2rem; display: flex; flex-direction: column; align-items: center; justify-content: center; background-image: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.15), transparent 60%); }
        .card { background: var(--surface); backdrop-filter: blur(12px); border: 1px solid var(--border); border-radius: 1rem; padding: 3rem; width: 100%; max-width: 500px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .card h1 { font-size: 1.75rem; margin-bottom: 2rem; color: var(--text-main); text-align: center; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.875rem; }
        .form-group select { width: 100%; padding: 0.75rem; border-radius: 0.5rem; background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border); color: white; outline: none; }
        .form-group select:focus { border-color: var(--primary); }
        .btn { width: 100%; padding: 0.75rem; background: var(--primary); color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: 0.2s; margin-top: 1rem; }
        .btn:hover { background: var(--primary-hover); transform: translateY(-2px); }
        .alert { padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-weight: 600; text-align: center; }
        .alert-success { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .back { display: block; text-align: center; margin-top: 1.5rem; color: var(--text-muted); text-decoration: none; }
        .back:hover { color: white; }
    </style>
</head>
<body>
    <div class="card">
        <h1>🎓 Apply for Certification</h1>
        


        <form method="POST" action="{{ route('certification.request') }}">
            @csrf
            <div class="form-group">
                <label>Select Student</label>
                <select name="student_id" required>
                    <option value="">Choose a student...</option>
                    @foreach($students as $s)
                        <option value="{{ $s->student_id }}">{{ $s->full_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label>Training Type (Badge)</label>
                <select name="training_type" required>
                    <option value="">Choose training type...</option>
                    @foreach($trainings as $t)
                        <option value="{{ $t->title }}">{{ $t->title }}</option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="btn">Submit Request</button>
        </form>
        
        <a href="{{ route('home') }}" class="back">← Return to Dashboard</a>
    </div>
    @include('components.toast')
</body>
</html>
