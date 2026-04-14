<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training & Donation | BRACU Medical</title>
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
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); min-height: 100vh; padding: 2rem; background-image: radial-gradient(circle at 50% 10%, rgba(59, 130, 246, 0.15), transparent 40%); }
        .header { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto 3rem; }
        .header h1 { font-weight: 800; font-size: 2rem; }
        .btn { padding: 0.75rem 1.5rem; background: var(--primary); color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: 0.2s; }
        .btn:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); }
        .btn-small { padding: 0.5rem 1rem; font-size: 0.875rem; }
        .btn-disabled { background: #475569; pointer-events: none; }
        .grid { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; max-width: 1200px; margin: 0 auto; }
        @media (max-width: 768px) { .grid { grid-template-columns: 1fr; } }
        .card { background: var(--surface); backdrop-filter: blur(12px); border: 1px solid var(--border); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem; }
        .card h2 { font-size: 1.25rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.75rem; color: var(--primary); }
        .alert { padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-weight: 600; }
        .alert-success { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .alert-error { background: rgba(244, 63, 94, 0.2); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.3); }
        .status-box { padding: 1rem; background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 0.5rem; margin-bottom: 1rem; }
        .form-group { display: flex; gap: 0.5rem; margin-top: 1rem; }
        select, input[type="date"] { padding: 0.75rem; border-radius: 0.5rem; background: rgba(15, 23, 42, 0.5); border: 1px solid var(--border); color: var(--text-main); flex: 1; outline: none; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border); }
        th { color: var(--text-muted); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 1px; }
        .badge { padding: 0.25rem 0.5rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; background: rgba(255,255,255,0.1); }
        .badge-pending { color: #fbbf24; background: rgba(251, 191, 36, 0.2); }
        .badge-confirmed { color: #34d399; background: rgba(16, 185, 129, 0.2); }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 Training & Donation Portal</h1>
        <a href="{{ route('home') }}" class="btn">← Dashboard</a>
    </div>

    <div class="grid">
        <!-- Sidebar: Status & Info -->
        <div>
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 0.75rem; margin-bottom: 1.5rem;">
                    <h2 style="margin-bottom: 0; border: none; padding: 0;">👤 Profile Status</h2>
                    <a href="{{ route('profile') }}" class="btn btn-small btn-outline">⚙️ Edit Profile</a>
                </div>

                @if($student)
                <div class="status-box">
                    <div style="color: var(--text-muted); font-size: 0.875rem;">Name</div>
                    <div style="font-weight: 600; font-size: 1.1rem; margin-bottom: 1rem;">{{ $student->full_name }}</div>
                    
                    <div style="color: var(--text-muted); font-size: 0.875rem;">Availability</div>
                    <div style="font-weight: 600; color: {{ $student->available == 'Available' ? 'var(--success)' : 'var(--accent)' }};">{{ $student->available ?? 'Not Set' }}</div>
                    
                    <form method="POST" action="{{ route('training.status') }}" class="form-group">
                        @csrf
                        <select name="available">
                            <option value="Available" {{ $student->available == 'Available' ? 'selected' : '' }}>Available</option>
                            <option value="Not Available" {{ $student->available == 'Not Available' ? 'selected' : '' }}>Not Available</option>
                        </select>
                        <button type="submit" class="btn btn-small">Update</button>
                    </form>
                </div>

                <div class="status-box" style="margin-top: 1.5rem;">
                    <div style="color: var(--text-muted); font-size: 0.875rem;">Last Blood Donation</div>
                    <div style="font-weight: 600;">{{ $student->last_donation_date ?? 'No record' }}</div>
                    
                    <form method="POST" action="{{ route('training.status') }}" class="form-group">
                        @csrf
                        <input type="date" name="last_donation_date" value="{{ $student->last_donation_date }}">
                        <button type="submit" class="btn btn-small">Save</button>
                    </form>
                </div>
                @else
                <div class="alert alert-error">Student profile not loaded (Simulated Auth Failed).</div>
                @endif
            </div>

            <div class="card">
                <h2>🩸 Donation History</h2>
                @forelse($donHistory as $don)
                    <div class="status-box" style="border-left: 3px solid var(--accent);">
                        <strong style="display: block; font-size: 1.1rem;">{{ $don->bag_count }} Bag(s)</strong>
                        <span style="color: var(--text-muted); font-size: 0.875rem;">{{ $don->donation_date }} — {{ $don->title }}, {{ $don->location }}</span>
                    </div>
                @empty
                    <p style="color: var(--text-muted);">No recorded donations.</p>
                @endforelse
            </div>
        </div>

        <!-- Main Content: Sessions -->
        <div>
            <div class="card">
                <h2>📚 Upcoming Training Sessions</h2>
                @if($sessions->isEmpty())
                    <p style="color: var(--text-muted);">No training sessions posted yet.</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Session Title</th>
                                <th>Date & Time</th>
                                <th>Capacity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sessions as $s)
                            <tr>
                                <td>
                                    <strong style="display: block;">{{ $s->title }}</strong>
                                    <span style="color: var(--text-muted); font-size: 0.875rem;">{{ $s->location }}</span>
                                </td>
                                <td>{{ date('M j', strtotime($s->date)) }} <br><span style="color: var(--text-muted); font-size: 0.875rem;">{{ date('g:i A', strtotime($s->time)) }}</span></td>
                                <td>{{ $s->count }} / {{ $s->capacity }}</td>
                                <td>
                                    @if($s->is_full)
                                        <span class="badge">Closed</span>
                                    @else
                                        <form method="POST" action="{{ route('training.register') }}">
                                            @csrf
                                            <input type="hidden" name="session_id" value="{{ $s->session_id }}">
                                            <button type="submit" class="btn btn-small">Register</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="card">
                <h2>📈 My Registrations</h2>
                @if($registrations->isEmpty())
                    <p style="color: var(--text-muted);">You have not registered for any sessions.</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Session</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $r)
                            <tr>
                                <td>
                                    <strong>{{ $r->title }}</strong>
                                    <br><span style="color: var(--text-muted); font-size: 0.875rem;">{{ $r->location }}</span>
                                </td>
                                <td>{{ date('M j', strtotime($r->date)) }} <br><span style="color: var(--text-muted); font-size: 0.875rem;">{{ date('g:i A', strtotime($r->time)) }}</span></td>
                                <td>
                                    <span class="badge badge-{{ strtolower($r->status) }}">{{ ucfirst($r->status) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
    @include('components.toast')
</body>
</html>
