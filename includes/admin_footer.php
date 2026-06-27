<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleAdminSidebar() {
    document.getElementById('adminSidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}

// Loading state otomatis untuk semua form submit
document.querySelectorAll('form').forEach(function (form) {
    form.addEventListener('submit', function () {
        const btn = form.querySelector('button[type="submit"]');
        if (btn && form.checkValidity()) {
            btn.dataset.originalText = btn.innerHTML;
            btn.classList.add('btn-loading');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';
        }
    });
});
</script>
</body>
</html>
</body>
</html>