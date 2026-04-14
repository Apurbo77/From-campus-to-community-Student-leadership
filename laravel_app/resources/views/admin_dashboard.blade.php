<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | BRACU Medical</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f172a;
            --surface: rgba(30, 41, 59, 0.7);
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --accent: #f43f5e;
            --accent-hover: #e11d48;
            --success: #10b981;
            --text-main: #f8fafc;
            --text-muted: #cbd5e1;
            --border: rgba(255, 255, 255, 0.1);
            --danger: #ef4444;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); min-height: 100vh; padding: 2rem; background-image: radial-gradient(circle at 15% 10%, rgba(59, 130, 246, 0.15), transparent 40%), radial-gradient(circle at 85% 90%, rgba(244, 63, 94, 0.15), transparent 40%); }
        .header { display: flex; justify-content: space-between; align-items: center; max-width: 1400px; margin: 0 auto 3rem; background: var(--surface); backdrop-filter: blur(12px); border: 1px solid var(--border); padding: 1.5rem 2rem; border-radius: 1rem; }
        .header h1 { font-weight: 800; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem; }
        .btn { padding: 0.5rem 1rem; background: var(--primary); color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: 0.2s; white-space: nowrap; }
        .btn:hover { background: var(--primary-hover); }
        .btn-danger { background: var(--danger); }
        .btn-danger:hover { background: #dc2626; }
        .btn-success { background: var(--success); }
        .btn-success:hover { background: #059669; }
        .grid { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; max-width: 1400px; margin: 0 auto; }
        @media (max-width: 1024px) { .grid { grid-template-columns: 1fr; } }
        .card { background: var(--surface); backdrop-filter: blur(12px); border: 1px solid var(--border); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem; }
        .card h2 { font-size: 1.25rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.75rem; color: var(--primary); }
        .form-group { margin-bottom: 1rem; }
        .form-group input, .form-group select { width: 100%; padding: 0.75rem; border-radius: 0.5rem; background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border); color: white; outline: none; }
        .form-group input:focus, .form-group select:focus { border-color: var(--primary); }
        .alert { padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-weight: 600; max-width: 1400px; margin: 0 auto 1.5rem; }
        .alert-success { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .alert-error { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border); }
        th { color: var(--text-muted); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 1px; }
        .badge { padding: 0.25rem 0.5rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
        .badge-pending { background: rgba(251, 191, 36, 0.2); color: #fbbf24; }
        .badge-confirmed { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }
        .badge-completed { background: rgba(16, 185, 129, 0.2); color: #34d399; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🛡️ System Administrator</h1>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <span style="color: var(--text-muted);">Welcome, <strong style="color: white;">{{ session('admin_name', 'Admin') }}</strong></span>
            <!-- Need to implement logout eventually, placeholder to home for now -->
            <a href="{{ route('home') }}" class="btn" style="background: rgba(255,255,255,0.1);">Logout / Leave</a>
        </div>
    </div>



    <div class="grid">
        <!-- LEFT COLUMN: CREATION PANELS -->
        <div>
            <div class="card">
                <h2>📚 Post Training Session</h2>
                <form method="POST" action="{{ route('admin.sessions.create') }}">
                    @csrf
                    <div class="form-group"><input type="text" name="title" placeholder="Title (e.g., CPR Basics)" required></div>
                    <div class="form-group"><input type="text" name="location" placeholder="Location (e.g., BRACU Auditorium)" required></div>
                    <div style="display: flex; gap: 1rem;">
                        <div class="form-group" style="flex: 1;"><input type="date" name="date" required></div>
                        <div class="form-group" style="flex: 1;"><input type="time" name="time" required></div>
                    </div>
                    <div class="form-group"><input type="number" name="capacity" placeholder="Capacity (e.g., 50)" required></div>
                    <button type="submit" class="btn" style="width: 100%;">Create Session</button>
                </form>
            </div>

            <div class="card" style="border-top: 3px solid var(--accent);">
                <h2 style="color: var(--accent);">🩸 Post Blood Camp</h2>
                <form method="POST" action="{{ route('admin.camps.create') }}">
                    @csrf
                    <div class="form-group"><input type="text" name="c_title" placeholder="Camp Title" required></div>
                    <div class="form-group"><input type="text" name="c_location" placeholder="Location" required></div>
                    <div style="display: flex; gap: 1rem;">
                        <div class="form-group" style="flex: 1;"><input type="date" name="c_date" required></div>
                        <div class="form-group" style="flex: 1;"><input type="time" name="c_time" required></div>
                    </div>
                    <button type="submit" class="btn btn-accent" style="width: 100%;">Create Camp</button>
                </form>
            </div>
        </div>

        <!-- RIGHT COLUMN: MANAGEMENT PANELS -->
        <div>
            <!-- Certifications -->
            <div class="card">
                <h2>🎓 Pending Certifications</h2>
                @if($pendingCertifications->isEmpty())
                    <p style="color: var(--text-muted);">No pending certification requests.</p>
                @else
                    <div style="overflow-x: auto;">
                        <table>
                            <thead><tr><th>Student</th><th>Requested Badge</th><th>Action</th></tr></thead>
                            <tbody>
                                @foreach($pendingCertifications as $cert)
                                <tr>
                                    <td><strong>{{ $cert->full_name }}</strong></td>
                                    <td>{{ $cert->badge_name }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.certifications.approve') }}" style="display: flex; gap: 0.5rem; align-items: center;">
                                            @csrf
                                            <input type="hidden" name="cert_id" value="{{ $cert->certification_id }}">
                                            <select name="badge_icon" required style="width: auto; padding: 0.5rem;">
                                                <option value="">Select Badge</option>
                                                <option value="CPR">CPR</option>
                                                <option value="ECG">ECG</option>
                                                <option value="INJECTION">INJECTION</option>
                                                <option value="FIRST AID">FIRST AID</option>
                                            </select>
                                            <button type="submit" class="btn btn-success">Approve</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Registrations -->
            <div class="card">
                <h2>📋 Training Registrations</h2>
                @if($recentRegistrations->isEmpty())
                    <p style="color: var(--text-muted);">No registrations yet.</p>
                @else
                    <div style="overflow-x: auto;">
                        <table>
                            <thead><tr><th>Student</th><th>Session</th><th>Status</th><th>Set Status</th></tr></thead>
                            <tbody>
                                @foreach($recentRegistrations as $reg)
                                <tr>
                                    <td><strong>{{ $reg->full_name }}</strong></td>
                                    <td>{{ $reg->title }}<br><span style="color: var(--text-muted); font-size: 0.8rem;">{{ $reg->date }}</span></td>
                                    <td><span class="badge badge-{{ strtolower($reg->status) }}">{{ $reg->status }}</span></td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.registrations.update') }}" style="display: flex; gap: 0.5rem;">
                                            @csrf
                                            <input type="hidden" name="reg_id" value="{{ $reg->id }}">
                                            <select name="new_status" style="width: auto; padding: 0.5rem;">
                                                <option value="pending" {{ $reg->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="confirmed" {{ $reg->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                <option value="completed" {{ $reg->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                            <button type="submit" class="btn">Update</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Student Management -->
            <div class="card">
                <h2 style="color: var(--danger);">⚠️ Student Enforcement</h2>
                <div style="overflow-x: auto; max-height: 400px; overflow-y: auto;">
                    <table>
                        <thead><tr><th>Student Name</th><th>Blood Group</th><th>Danger Zone</th></tr></thead>
                        <tbody>
                            @foreach($students as $s)
                            <tr>
                                <td>{{ $s->full_name }}</td>
                                <td>{{ $s->blood_group }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.students.delete') }}" onsubmit="return confirm('WARNING: This will permanently delete this student and ALL their donations, registrations, and history! Proceed?');">
                                        @csrf
                                        <input type="hidden" name="student_id" value="{{ $s->student_id }}">
                                        <button type="submit" class="btn btn-danger btn-small">Delete Student & Data</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    @include('components.toast')
</body>
</html>
