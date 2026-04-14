<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Matching | BRACU Medical</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f172a;
            --surface: rgba(30, 41, 59, 0.7);
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --accent: #f43f5e;
            --accent-hover: #e11d48;
            --text-main: #f8fafc;
            --text-muted: #cbd5e1;
            --border: rgba(255, 255, 255, 0.1);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); min-height: 100vh; padding: 2rem; background-image: radial-gradient(circle at 80% 20%, rgba(244, 63, 94, 0.15), transparent 40%); }
        .header { display: flex; justify-content: space-between; align-items: center; max-width: 1000px; margin: 0 auto 3rem; }
        .header h1 { font-weight: 800; font-size: 2rem; }
        .btn { padding: 0.75rem 1.5rem; background: var(--primary); color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: 0.2s; }
        .btn:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); }
        .btn-accent { background: var(--accent); }
        .btn-accent:hover { background: var(--accent-hover); box-shadow: 0 4px 12px rgba(244, 63, 94, 0.4); }
        .container { max-width: 1000px; margin: 0 auto; }
        .card { background: var(--surface); backdrop-filter: blur(12px); border: 1px solid var(--border); border-radius: 1rem; padding: 2rem; margin-bottom: 2rem; }
        .alert { padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-weight: 600; }
        .alert-success { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .alert-error { background: rgba(244, 63, 94, 0.2); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.3); }
        
        .search-form { display: flex; gap: 1rem; flex-wrap: wrap; }
        .input-field { background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem 1rem; border-radius: 0.5rem; outline: none; flex: 1; min-width: 200px; }
        .input-field:focus { border-color: var(--primary); }
        .input-field option { background: var(--bg); color: var(--text-main); }
        
        .donor-list { display: grid; gap: 1rem; margin-top: 1rem; }
        .donor-item { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 1.25rem; border-radius: 0.75rem; display: flex; justify-content: space-between; align-items: center; transition: background 0.2s; }
        .donor-item:hover { background: rgba(255,255,255,0.04); }
        @media (max-width: 600px) { .donor-item { flex-direction: column; align-items: flex-start; gap: 1rem; } .donor-item button { width: 100%; } }
        
        .badge { padding: 0.25rem 0.5rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; background: rgba(244, 63, 94, 0.2); color: #fb7185; }
        
        /* Modal Styles */
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); display: none; justify-content: center; align-items: center; z-index: 1000; opacity: 0; transition: opacity 0.3s; }
        .modal-overlay.active { display: flex; opacity: 1; }
        .modal { background: var(--bg); border: 1px solid var(--border); border-radius: 1rem; padding: 2rem; width: 100%; max-width: 500px; transform: translateY(20px); transition: transform 0.3s; }
        .modal-overlay.active .modal { transform: translateY(0); }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.875rem; }
        .form-group input, .form-group select { width: 100%; padding: 0.75rem; border-radius: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid var(--border); color: white; outline: none; }
        .form-group input:focus, .form-group select:focus { border-color: var(--accent); }
    </style>
</head>
<body>
    <div class="header">
        <h1>🩸 Blood Matching Intelligence</h1>
        <a href="{{ route('home') }}" class="btn">← Dashboard</a>
    </div>

    <div class="container">


        <div class="card">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Find Eligible Donors</h2>
            <form method="GET" action="{{ route('blood_matching') }}" class="search-form">
                <input type="text" name="area" value="{{ request('area') }}" placeholder="Enter area (e.g., Banani)" class="input-field">
                <select name="blood_group" class="input-field" style="flex: 0 0 auto;">
                    <option value="">Any blood group</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="search" value="1">
                <button type="submit" class="btn">Search Donors</button>
            </form>
        </div>

        @if(request()->has('search'))
        <div class="card">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Search Results</h2>
            @if($students->isEmpty())
                <p style="color: var(--text-muted);">No donors found matching your criteria.</p>
            @else
                <div class="donor-list">
                    @foreach($students as $student)
                    <div class="donor-item">
                        <div>
                            <strong style="font-size: 1.1rem;">{{ $student->full_name }}</strong>
                            <span class="badge" style="margin-left: 0.5rem;">{{ $student->blood_group }}</span>
                            <div style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">
                                📍 {{ $student->area }} | 📞 {{ $student->phone }}
                            </div>
                        </div>
                        <button onclick="openModal('{{ addslashes($student->full_name) }}', '{{ $student->blood_group }}', '{{ addslashes($student->area) }}')" class="btn btn-accent">Request Blood</button>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal-overlay" id="requestModal">
        <div class="modal">
            <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; color: var(--accent);">Emergency Blood Request</h3>
            <p id="modalRecipient" style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.875rem;"></p>
            
            <form method="POST" action="{{ route('blood_matching.request') }}">
                @csrf
                <input type="hidden" name="blood_group" id="modalBloodGroup">
                
                <div class="form-group">
                    <label>Your Full Name</label>
                    <input type="text" name="name" required placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label>Your Phone Number</label>
                    <input type="tel" name="phone" required placeholder="017...">
                </div>
                <div class="form-group">
                    <label>Hospital / Location</label>
                    <input type="text" name="location" required placeholder="Evercare Hospital">
                </div>
                <div class="form-group">
                    <label>Urgency Level</label>
                    <select name="urgency_level" required>
                        <option value="high">High (Immediate)</option>
                        <option value="medium">Medium (Within 24 Hours)</option>
                        <option value="low">Low (Scheduled Surgery)</option>
                    </select>
                </div>
                
                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-accent" style="flex: 1;">Submit Priority Request</button>
                    <button type="button" class="btn" style="background: rgba(255,255,255,0.1);" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(donorName, bloodGroup, area) {
            const modal = document.getElementById('requestModal');
            document.getElementById('modalBloodGroup').value = bloodGroup;
            document.getElementById('modalRecipient').innerHTML = `Routing request primarily to <strong>${donorName}</strong> (${bloodGroup}) matching in ${area}.`;
            
            modal.classList.add('active');
        }

        function closeModal() {
            document.getElementById('requestModal').classList.remove('active');
        }

        // Close on outside click
        document.getElementById('requestModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
    @include('components.toast')
</body>
</html>
