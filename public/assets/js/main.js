// Initialize Toasts automatically
document.addEventListener('DOMContentLoaded', function () {
    const toastElList = document.querySelectorAll('.toast');
    const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl));
    toastList.forEach(toast => toast.show());
    
    // Add loading state to forms on submit
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"], button:not([type="button"])');
            if (btn) {
                if (!btn.hasAttribute('data-loading')) {
                    btn.setAttribute('data-loading', 'true');
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
                    btn.classList.add('disabled');
                }
            }
        });
    });
});
