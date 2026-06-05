importScripts('https://www.gstatic.com/firebasejs/10.14.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.14.1/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyCC0wePGXNWdqfDlfwx0IjM-2HlQk-ihCI",
    authDomain: "bizguide-999d7.firebaseapp.com",
    projectId: "bizguide-999d7",
    storageBucket: "bizguide-999d7.firebasestorage.app",
    messagingSenderId: "794238460044",
    appId: "1:794238460044:web:5e9a27361ce106b1511ba9"
});

const messaging = firebase.messaging();

// Fallback icon as a data URI so notifications display even if asset files are missing
var FALLBACK_ICON = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAIAAAD8GO2jAAAAKklEQVR42mPQlc6kKWIYtWDUglELRi0YtWDUglELRi0YtWDUglELhooFAFecxB+wIO0LAAAAAElFTkSuQmCC';

function getIcon(payload) {
    if (payload.notification && payload.notification.icon) return payload.notification.icon;
    if (payload.data && payload.data.icon) return payload.data.icon;
    // Try scope-relative path first, fall back to data URI
    try {
        return new URL('assets/icons/icon-192.png', self.registration.scope).href;
    } catch (e) {
        return FALLBACK_ICON;
    }
}

function getBadge(payload) {
    if (payload.data && payload.data.badge) return payload.data.badge;
    try {
        return new URL('assets/icons/icon-96.png', self.registration.scope).href;
    } catch (e) {
        return FALLBACK_ICON;
    }
}

function getClickUrl(payload) {
    if (payload.fcmOptions && payload.fcmOptions.link) return payload.fcmOptions.link;
    if (payload.data && payload.data.click_action) return payload.data.click_action;
    return self.registration.scope;
}

messaging.onBackgroundMessage(function(payload) {
    var title = (payload.notification && payload.notification.title)
        ? payload.notification.title
        : (payload.data && payload.data.title ? payload.data.title : 'BizGuide');

    var body = (payload.notification && payload.notification.body)
        ? payload.notification.body
        : (payload.data && payload.data.body ? payload.data.body : '');

    return self.registration.showNotification(title, {
        body:    body,
        icon:    getIcon(payload),
        badge:   getBadge(payload),
        vibrate: [200, 100, 200],
        data:    Object.assign({}, payload.data || {}, { url: getClickUrl(payload) })
    });
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    var url = (event.notification.data && event.notification.data.url)
        ? event.notification.data.url
        : self.registration.scope;

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(clientList) {
            for (var i = 0; i < clientList.length; i++) {
                var c = clientList[i];
                if (c.url === url && 'focus' in c) return c.focus();
            }
            for (var j = 0; j < clientList.length; j++) {
                if ('navigate' in clientList[j]) {
                    return clientList[j].navigate(url).then(function(c) {
                        return c && c.focus ? c.focus() : null;
                    });
                }
            }
            if (clients.openWindow) return clients.openWindow(url);
        })
    );
});

// Handle raw push events for browsers that bypass firebase onBackgroundMessage
self.addEventListener('push', function(event) {
    if (!event.data) return;
    var payload;
    try { payload = event.data.json(); } catch (e) { return; }
    // Firebase compat handles notification payloads; only handle data-only messages here
    if (payload.notification) return;
    if (payload.data && payload.data['google.c.a.e']) return; // Firebase analytics marker

    var title = (payload.data && payload.data.title) ? payload.data.title : 'BizGuide';
    var body  = (payload.data && payload.data.body)  ? payload.data.body  : '';
    var url   = getClickUrl(payload);

    event.waitUntil(
        self.registration.showNotification(title, {
            body:    body,
            icon:    getIcon(payload),
            badge:   getBadge(payload),
            vibrate: [200, 100, 200],
            data:    { url: url }
        })
    );
});
