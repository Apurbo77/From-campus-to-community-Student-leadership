@if(session('success') || session('error') || $errors->any())
<div id="toast-container" style="position: fixed; bottom: 2rem; right: 2rem; z-index: 9999; display: flex; flex-direction: column; gap: 1rem;">
    @if(session('success'))
    <div class="toast toast-success">
        <div class="toast-icon" style="font-size: 1.5rem;">✅</div>
        <div class="toast-content" style="flex: 1;">
            <h4 style="margin:0; font-size: 1rem; color: #10b981;">Success</h4>
            <p style="margin: 0.25rem 0 0; font-size: 0.875rem; opacity: 0.9; line-height: 1.4;">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;color:white;cursor:pointer;font-size:1.5rem; opacity: 0.5; padding: 0;">&times;</button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="toast toast-error">
        <div class="toast-icon" style="font-size: 1.5rem;">❌</div>
        <div class="toast-content" style="flex: 1;">
            <h4 style="margin:0; font-size: 1rem; color: #ef4444;">Action Failed</h4>
            <p style="margin: 0.25rem 0 0; font-size: 0.875rem; opacity: 0.9; line-height: 1.4;">{{ session('error') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;color:white;cursor:pointer;font-size:1.5rem; opacity: 0.5; padding: 0;">&times;</button>
    </div>
    @endif
    
    @if($errors->any())
    <div class="toast toast-error">
        <div class="toast-icon" style="font-size: 1.5rem;">⚠️</div>
        <div class="toast-content" style="flex: 1;">
            <h4 style="margin:0; font-size: 1rem; color: #ef4444;">Validation Error</h4>
            <ul style="margin: 0.25rem 0 0; font-size: 0.875rem; opacity: 0.9; padding-left: 1.25rem;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;color:white;cursor:pointer;font-size:1.5rem; opacity: 0.5; padding: 0;">&times;</button>
    </div>
    @endif
</div>

<style>
    .toast {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 1.5rem;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        color: white;
        animation: slideInToast 0.5s cubic-bezier(0.16, 1, 0.3, 1), fadeOutToast 0.5s ease-in 4.5s forwards;
        min-width: 320px;
        max-width: 400px;
    }
    .toast-success { border-left: 4px solid #10b981; }
    .toast-error { border-left: 4px solid #ef4444; }
    
    @keyframes slideInToast {
        from { transform: translateX(120%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes fadeOutToast {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(120%); }
    }
</style>
<script>
    setTimeout(() => {
        const container = document.getElementById('toast-container');
        if(container) {
            setTimeout(() => {
                if(container.parentNode) container.remove();
            }, 5500); // Give enough time for CSS animation to finish
        }
    }, 100);
</script>
@endif
