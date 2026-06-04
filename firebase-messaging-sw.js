importScripts('https://www.gstatic.com/firebasejs/12.14.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/12.14.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyCC0wePGXNWdqfDlfwx0IjM-2HlQk-ihCI",
    authDomain: "bizguide-999d7.firebaseapp.com",
    projectId: "bizguide-999d7",
    storageBucket: "bizguide-999d7.firebasestorage.app",
    messagingSenderId: "794238460044",
    appId: "1:794238460044:web:5e9a27361ce106b1511ba9"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
    const notificationTitle = (payload.notification && payload.notification.title)
        ? payload.notification.title
        : (payload.data && payload.data.title ? payload.data.title : 'BizGuide');

    const notificationOptions = {
        body: (payload.notification && payload.notification.body)
            ? payload.notification.body
            : (payload.data && payload.data.body ? payload.data.body : ''),
        icon: new URL('assets/icons/icon-192.png', self.registration.scope).href,
        badge: new URL('assets/icons/icon-96.png', self.registration.scope).href,
        data: payload.data || {}
    };

    if (payload.data && payload.data.click_action) {
        notificationOptions.data.url = payload.data.click_action;
    }

    return self.registration.showNotification(notificationTitle, notificationOptions);
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    const url = (event.notification.data && event.notification.data.url)
        ? event.notification.data.url
        : '/';
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(clientList) {
            for (var i = 0; i < clientList.length; i++) {
                if (clientList[i].url === url && 'focus' in clientList[i]) {
                    return clientList[i].focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});
