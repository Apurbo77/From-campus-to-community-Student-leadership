<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings | BRACU Medical</title>
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
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); min-height: 100vh; padding: 2rem; background-image: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.15), transparent 60%); }
        .container { max-width: 800px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .card { background: var(--surface); backdrop-filter: blur(12px); border: 1px solid var(--border); border-radius: 1rem; padding: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .card h2 { font-size: 1.5rem; margin-bottom: 1.5rem; color: var(--primary); border-bottom: 1px solid var(--border); padding-bottom: 0.75rem; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
        .form-group { margin-bottom: 1.25rem; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-group label { display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.875rem; }
        .form-group input { width: 100%; padding: 0.75rem; border-radius: 0.5rem; background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border); color: white; outline: none; transition: 0.2s; }
        .form-group input:focus { border-color: var(--primary); }
        .form-group input[readonly] { background: transparent; color: var(--text-muted); border: 1px dashed var(--border); cursor: not-allowed; }
        .btn { display: inline-block; padding: 0.75rem 1.5rem; background: var(--primary); color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: 0.2s; text-decoration: none; }
        .btn:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: white; }
        .btn-outline:hover { background: rgba(255,255,255,0.05); }
    </style>
</head>
<body>
    @include('components.toast')

    <div class="container">
        <div class="header">
            <div>
                <h1 style="font-weight: 800; font-size: 2rem;">⚙️ Personal Settings</h1>
                <p style="color: var(--text-muted); margin-top: 0.25rem;">Update your profile information</p>
            </div>
            <a href="{{ route('training') }}" class="btn btn-outline">← Back to Dashboard</a>
        </div>

        <div class="card">
            <h2>Profile Information</h2>
            
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>G-Suite Address</label>
                        <input type="email" value="{{ $student->gsuit }}" readonly title="G-Suite cannot be changed">
                    </div>
                    
                    <div class="form-group">
                        <label>Blood Group</label>
                        <input type="text" value="{{ $student->blood_group }}" readonly title="Blood Group cannot be changed automatically">
                    </div>

                    <div class="form-group full-width">
                        <label>Full Name</label>
                        <input type="text" name="full_name" value="{{ $student->full_name }}" required>
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="{{ $student->phone }}" required>
                    </div>

                    <div class="form-group">
                        <label>Profile URL (e.g. LinkedIn or Facebook)</label>
                        <input type="url" name="profile_url" value="{{ $student->profile_url }}" placeholder="https://...">
                    </div>

                    <div class="form-group">
                        <label>Area / Neighborhood</label>
                        <input type="text" name="area" value="{{ $student->area }}" required>
                    </div>

                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" value="{{ $student->city }}" required>
                    </div>
                </div>

                <h2 style="margin-top: 2rem; color: var(--accent);">Security</h2>
                
                <div class="form-group">
                    <label>New Password (leave blank to keep current password)</label>
                    <input type="password" name="password" placeholder="Enter new password to change">
                </div>

                <div style="margin-top: 2rem; border-top: 1px solid var(--border); padding-top: 1.5rem; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
