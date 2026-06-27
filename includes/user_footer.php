<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleUserSidebar() {
    document.getElementById('userSidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}

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