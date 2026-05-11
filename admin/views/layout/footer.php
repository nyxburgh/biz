</div><!-- /#main -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-dismiss alerts after 4s
document.querySelectorAll('.alert.fade.show').forEach(function(el) {
  setTimeout(function() {
    var a = bootstrap.Alert.getOrCreateInstance(el);
    if (a) a.close();
  }, 4000);
});
// Confirm dialogs via data-confirm attribute
document.querySelectorAll('[data-confirm]').forEach(function(btn) {
  btn.addEventListener('click', function(e) {
    if (!confirm(this.dataset.confirm || 'Are you sure?')) e.preventDefault();
  });
});
// Mobile sidebar
function toggleSidebar() {
  document.getElementById('sb').classList.toggle('open');
  document.getElementById('sb-overlay').classList.toggle('open');
}
document.querySelectorAll('#sb nav a').forEach(function(a) {
  a.addEventListener('click', function() {
    if (window.innerWidth <= 768) toggleSidebar();
  });
});
</script>
<?= $extraJs ?? '' ?>
</body>
</html>
