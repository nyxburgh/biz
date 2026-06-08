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
<script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-messaging-compat.js"></script>
<script>
(function() {
  var firebaseConfig = {
    apiKey: "AIzaSyCC0wePGXNWdqfDlfwx0IjM-2HlQk-ihCI",
    authDomain: "bizguide-999d7.firebaseapp.com",
    projectId: "bizguide-999d7",
    storageBucket: "bizguide-999d7.firebasestorage.app",
    messagingSenderId: "794238460044",
    appId: "1:794238460044:web:5e9a27361ce106b1511ba9"
  };
  var VAPID_KEY = "BHBKh3Ro89NFSZ1LM9hP1gMiAkLBuEzJyJY3mSB3ZgVhsGwBWdGQkqFyqtLxbC0GQzqcvqLlTVolQDXbCDV40qM";
  var BASE          = "<?= rtrim(BASE_URL, '/') ?>";
  var SW_URL        = BASE + "/firebase-messaging-sw.js";
  var TOKEN_URL     = BASE + "/fcm-token.php";

  var supportsSecurePush = window.isSecureContext
    || location.hostname === "localhost"
    || location.hostname === "127.0.0.1";

  if (!supportsSecurePush
    || !("serviceWorker" in navigator)
    || !("Notification" in window)
    || !("fetch" in window)) return;

  if (firebase.messaging.isSupported && !firebase.messaging.isSupported()) return;

  if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);
  var messaging;
  try { messaging = firebase.messaging(); } catch (e) { console.warn("FCM admin init:", e); return; }

  function saveToken(token) {
    fetch(TOKEN_URL, {
      method: "POST",
      credentials: "same-origin",
      cache: "no-store",
      headers: { "Content-Type": "application/json", "Accept": "application/json" },
      body: JSON.stringify({ token: token, city_slug: null, device_type: "admin-web" })
    }).catch(function(e) { console.warn("FCM admin token save:", e); });
  }

  function getToken(retried) {
    if (Notification.permission !== "granted") return;
    navigator.serviceWorker.ready.then(function(swReg) {
      return messaging.getToken({ vapidKey: VAPID_KEY, serviceWorkerRegistration: swReg });
    }).then(function(t) { if (t) saveToken(t); })
      .catch(function(e) {
        console.warn("FCM admin getToken:", e);
        var isStorageError = e.name === "VersionError" || e.name === "AbortError" || e.message.includes("VersionError");
        if (!retried && isStorageError && window.indexedDB) {
          try {
            window.indexedDB.deleteDatabase("firebase-messaging-database");
            window.indexedDB.deleteDatabase("fcm_token_details_db");
            setTimeout(function() { getToken(true); }, 500);
          } catch(err) { console.error("FCM admin cleanup failed:", err); }
        }
      });
  }


  function init() {
    navigator.serviceWorker.register(SW_URL)
      .then(function() {
        if (Notification.permission === "granted") {
          getToken();
        } else if (Notification.permission !== "denied") {
          Notification.requestPermission().then(function(p) {
            if (p === "granted") getToken();
          });
        }
      })
      .catch(function(e) { console.warn("FCM admin SW:", e); });
  }

  messaging.onMessage(function(payload) {
    var title = (payload.notification && payload.notification.title)
      ? payload.notification.title
      : (payload.data && payload.data.title ? payload.data.title : "BizGuide");
    var body = (payload.notification && payload.notification.body)
      ? payload.notification.body
      : (payload.data && payload.data.body ? payload.data.body : "");
    if (Notification.permission === "granted") {
      navigator.serviceWorker.ready.then(function(reg) {
        reg.showNotification(title, { body: body });
      });
    }
  });

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
</script>
</body>
</html>
