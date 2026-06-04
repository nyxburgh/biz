</main>
<footer class="site-footer-main" style="background:linear-gradient(135deg,#2d1b69,#1e1245);color:rgba(255,255,255,0.7);padding:40px 20px 24px;margin-top:40px">
  <div style="max-width:1100px;margin:0 auto">
    <div style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:28px;margin-bottom:28px">
      <div>
        <h3 style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.1rem;color:#fff;margin-bottom:8px">
          BizGuide <span style="color:#a78bfa"><?= htmlspecialchars($cityName) ?></span>
        </h3>
        <p style="font-size:0.8rem;line-height:1.7;max-width:220px">Your trusted local business directory.</p>
      </div>
      <div>
        <h4 style="font-size:0.8rem;font-weight:700;color:#fff;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:10px">Quick Links</h4>
        <div style="display:flex;flex-direction:column;gap:6px;font-size:0.8rem">
          <a href="<?= $cityUrl ?>" style="color:rgba(255,255,255,0.55)">Home</a>
          <a href="<?= $cityUrl ?>/search" style="color:rgba(255,255,255,0.55)">All Businesses</a>
          <a href="<?= $cityUrl ?>/post-ad" style="color:rgba(255,255,255,0.55)">Post Free Ad</a>
          <a href="<?= $cityUrl ?>/login" style="color:rgba(255,255,255,0.55)">Login / Register</a>
        </div>
      </div>
      <div>
        <h4 style="font-size:0.8rem;font-weight:700;color:#fff;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:10px">Plans</h4>
        <div style="display:flex;flex-direction:column;gap:6px;font-size:0.8rem">
          <a href="<?= $cityUrl ?>/post-ad" style="color:rgba(255,255,255,0.55)">Free Listing</a>
          <a href="<?= $cityUrl ?>/upgrade" style="color:rgba(255,255,255,0.55)">Basic — ₹299</a>
          <a href="<?= $cityUrl ?>/upgrade" style="color:rgba(255,255,255,0.55)">Premium — ₹599</a>
          <a href="<?= $cityUrl ?>/upgrade" style="color:rgba(255,255,255,0.55)">Pro — ₹999</a>
        </div>
      </div>
    </div>
    <div style="border-top:1px solid rgba(255,255,255,0.1);padding-top:18px;font-size:0.76rem;color:rgba(255,255,255,0.4)">
      © <?= date('Y') ?> BizGuide <?= htmlspecialchars($cityName) ?>. All rights reserved.
    </div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $extraJs ?? '' ?>
<script src="https://www.gstatic.com/firebasejs/12.14.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/12.14.0/firebase-messaging-compat.js"></script>
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
  var APP_PATH = new URL("<?= rtrim(BASE_URL, '/') ?>/", window.location.href).pathname.replace(/\/$/, "");
  var FCM_TOKEN_ENDPOINT = APP_PATH + "/fcm-token.php";
  var CITY_SLUG = "<?= defined('CITY_SLUG') ? htmlspecialchars(CITY_SLUG) : '' ?>";

  if (!("serviceWorker" in navigator) || !("Notification" in window)) return;
  if (firebase.messaging.isSupported && !firebase.messaging.isSupported()) return;

  if (!firebase.apps.length) {
    firebase.initializeApp(firebaseConfig);
  }
  try {
    var messaging = firebase.messaging();
  } catch (e) {
    console.warn("FCM unsupported:", e);
    return;
  }

  function saveToken(token) {
    fetch(FCM_TOKEN_ENDPOINT, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ token: token, city_slug: CITY_SLUG })
    }).then(function(response) {
      if (!response.ok) {
        return response.text().then(function(text) {
          console.warn("FCM token save failed:", response.status, text);
        });
      }
    }).catch(function(e) { console.warn("FCM token save failed:", e); });
  }

  function requestAndGetToken(swReg) {
    messaging.getToken({ vapidKey: VAPID_KEY, serviceWorkerRegistration: swReg })
      .then(function(token) {
        if (token) { saveToken(token); }
      })
      .catch(function(e) { console.warn("FCM getToken:", e); });
  }

  function requestPermission(swReg) {
    Notification.requestPermission().then(function(perm) {
      if (perm === "granted") requestAndGetToken(swReg);
      var prompt = document.getElementById("fcm-mobile-prompt");
      if (prompt) prompt.remove();
    });
  }

  function showMobilePrompt(swReg) {
    if (Notification.permission !== "default" || document.getElementById("fcm-mobile-prompt")) return;
    if (!window.matchMedia("(pointer: coarse)").matches && window.innerWidth > 768) return;

    var btn = document.createElement("button");
    btn.id = "fcm-mobile-prompt";
    btn.type = "button";
    btn.textContent = "Enable alerts";
    btn.style.cssText = "position:fixed;right:16px;bottom:16px;z-index:1050;border:0;border-radius:999px;background:#2d1b69;color:#fff;font:700 14px/1.1 system-ui,-apple-system,Segoe UI,sans-serif;padding:13px 16px;box-shadow:0 8px 24px rgba(45,27,105,.28)";
    btn.addEventListener("click", function() { requestPermission(swReg); });
    document.body.appendChild(btn);
  }

  function initFCM() {
    navigator.serviceWorker.register(APP_PATH + "/firebase-messaging-sw.js", { scope: (APP_PATH || "") + "/" })
      .then(function(swReg) {
        if (Notification.permission === "granted") {
          requestAndGetToken(swReg);
        } else if (Notification.permission !== "denied") {
          showMobilePrompt(swReg);
          if (!window.matchMedia("(pointer: coarse)").matches && window.innerWidth > 768) requestPermission(swReg);
        }
      }).catch(function(e) { console.warn("FCM SW registration error:", e); });
  }

  messaging.onMessage(function(payload) {
    var title = (payload.notification && payload.notification.title) ? payload.notification.title : ((payload.data && payload.data.title) ? payload.data.title : "BizGuide");
    var body  = (payload.notification && payload.notification.body)  ? payload.notification.body  : ((payload.data && payload.data.body)  ? payload.data.body  : "");
    if (Notification.permission === "granted") {
      navigator.serviceWorker.ready.then(function(reg) {
        reg.showNotification(title, {
          body: body,
          data: payload.data || {}
        });
      });
    }
  });

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initFCM);
  } else {
    initFCM();
  }
})();
</script>
</body>
</html>
