</main>
<footer class="site-footer-main" style="background:#1e1245;color:rgba(255,255,255,0.5);padding:24px 20px;margin-top:40px;border-top:1px solid rgba(255,255,255,0.05)">
  <div style="max-width:1100px;margin:0 auto;font-size:0.75rem">
    Â© <?= date('Y') ?> BizGuide <?= htmlspecialchars($cityName) ?>. All rights reserved.
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
  // Use CITY_URL (PHP-injected) so SW and token endpoint are always same-origin as the page
  var SW_URL             = "<?= rtrim(defined('CITY_URL') ? CITY_URL : BASE_URL, '/') ?>/firebase-messaging-sw.js";
  var FCM_TOKEN_ENDPOINT = "<?= rtrim(BASE_URL, '/') ?>/fcm-token.php";
  var ICON_URL           = "<?= rtrim(BASE_URL, '/') ?>/assets/icons/icon-192.png";
  var BADGE_URL          = "<?= rtrim(BASE_URL, '/') ?>/assets/icons/icon-96.png";
  var CITY_SLUG          = "<?= defined('CITY_SLUG') ? htmlspecialchars(CITY_SLUG) : '' ?>";
  var PROMPT_ID          = "fcm-mobile-prompt";
  var supportsSecurePush = window.isSecureContext || location.hostname === "localhost" || location.hostname === "127.0.0.1";

  if (!supportsSecurePush || !("serviceWorker" in navigator) || !("Notification" in window) || !("fetch" in window)) return;
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
      credentials: "same-origin",
      cache: "no-store",
      headers: { "Content-Type": "application/json", "Accept": "application/json" },
      body: JSON.stringify({
        token: token,
        city_slug: CITY_SLUG,
        device_type: isLikelyMobile() ? "mobile-web" : "web"
      })
    }).then(function(response) {
      if (!response.ok) {
        return response.text().then(function(text) {
          console.warn("FCM token save failed:", response.status, text);
        });
      }
      return response.json().catch(function() { return {}; }).then(function(data) {
        if (!data.success) console.warn("FCM token save response:", data);
      });
    }).catch(function(e) { console.warn("FCM token save failed:", e); });
  }

  function requestAndGetToken(retried) {
    if (Notification.permission !== "granted") return;
    navigator.serviceWorker.ready.then(function(swReg) {
      return messaging.getToken({ vapidKey: VAPID_KEY, serviceWorkerRegistration: swReg }).then(function(token) {
        if (token) { saveToken(token); }
      }).catch(function(e) {
        console.warn("FCM getToken:", e);
        var msg = e.message || "";
        var isStorageError = e.name === "VersionError" || e.name === "AbortError" || msg.includes("VersionError") || msg.includes("storage error") || msg.includes("push service error");
        if (!retried && isStorageError) {
          try {
            if (window.indexedDB) {
              window.indexedDB.deleteDatabase("firebase-messaging-database");
              window.indexedDB.deleteDatabase("fcm_token_details_db");
            }
            if (msg.includes("registration") || msg.includes("push service")) {
              swReg.unregister().then(function() { console.log("FCM SW unregistered for reset"); });
            }
            setTimeout(function() { requestAndGetToken(true); }, 800);
          } catch(err) { console.error("FCM cleanup failed:", err); }
        }
      });
    });
  }

  function requestPermission() {
    Notification.requestPermission().then(function(perm) {
      if (perm === "granted") requestAndGetToken();
      var prompt = document.getElementById(PROMPT_ID);
      if (prompt) prompt.remove();
    });
  }

  function isLikelyMobile() {
    return window.matchMedia("(pointer: coarse)").matches || window.innerWidth <= 768;
  }

  function showMobilePrompt() {
    if (Notification.permission !== "default" || document.getElementById(PROMPT_ID)) return;
    if (!isLikelyMobile()) return;

    var btn = document.createElement("button");
    btn.id = PROMPT_ID;
    btn.type = "button";
    btn.textContent = "Enable alerts";
    btn.setAttribute("aria-label", "Enable BizGuide alerts");
    btn.style.cssText = "position:fixed;right:16px;bottom:calc(88px + env(safe-area-inset-bottom));z-index:10000;border:0;border-radius:999px;background:#2d1b69;color:#fff;font:700 14px/1.1 system-ui,-apple-system,Segoe UI,sans-serif;padding:13px 16px;box-shadow:0 8px 24px rgba(45,27,105,.28);touch-action:manipulation";
    btn.addEventListener("click", function() { requestPermission(); });
    document.body.appendChild(btn);
  }

  function bindResumeChecks() {
    window.addEventListener("focus", function() { requestAndGetToken(); });
    document.addEventListener("visibilitychange", function() {
      if (!document.hidden) requestAndGetToken();
    });
  }

  function initFCM() {
    // Register without explicit scope so the browser uses the SW script directory as scope.
    // This avoids requiring Service-Worker-Allowed headers and works on all servers (Nginx/Apache).
    navigator.serviceWorker.register(SW_URL)
      .then(function() {
        bindResumeChecks();
        if (Notification.permission === "granted") {
          requestAndGetToken();
        } else if (Notification.permission !== "denied") {
          showMobilePrompt();
          if (!isLikelyMobile()) requestPermission();
        }
      }).catch(function(e) { console.warn("FCM SW registration error:", e); });
  }

  messaging.onMessage(function(payload) {
    var title = (payload.notification && payload.notification.title) ? payload.notification.title : ((payload.data && payload.data.title) ? payload.data.title : "BizGuide");
    var body  = (payload.notification && payload.notification.body)  ? payload.notification.body  : ((payload.data && payload.data.body)  ? payload.data.body  : "");
    var url   = (payload.fcmOptions && payload.fcmOptions.link) ? payload.fcmOptions.link : ((payload.data && payload.data.click_action) ? payload.data.click_action : null);
    if (Notification.permission === "granted") {
      navigator.serviceWorker.ready.then(function(reg) {
        reg.showNotification(title, {
          body: body,
          icon: ICON_URL,
          badge: BADGE_URL,
          data: Object.assign({}, payload.data || {}, url ? { url: url } : {}),
          vibrate: [200, 100, 200]
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
