const pushServiceWorkerPath = "/push_service_worker_example.js";

// VAPIDで使用するサーバ公開鍵
const applicationServerKey =
    "BMBlr6YznhYMX3NgcWIDRxZXs0sh7tCv7_YCsWcww0ZCv9WGg-tRCXfMEHTiBPCksSqeve1twlbmVAZFv7GSuj0";

function urlBase64ToUint8Array(base64String) {
    const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, "+")
        .replace(/_/g, "/");

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

function checkNotificationPermission() {
    return new Promise((resolve, reject) => {
        if (Notification.permission === "denied") {
            return reject(new Error("Push messages are blocked."));
        }

        if (Notification.permission === "granted") {
            return resolve();
        }

        if (Notification.permission === "default") {
            return Notification.requestPermission().then(result => {
                if (result !== "granted") {
                    reject(new Error("Bad permission result"));
                } else {
                    resolve();
                }
            });
        }

        return reject(new Error("Unknown permission"));
    });
}

// function encodeBase64URL(text) {
//     if (!text) return null;
//     return btoa(String.fromCharCode.apply(null, new Uint8Array(text)));
// }

function publish(registration) {
    return registration.pushManager.getSubscription().then(subscription => {
        if (!subscription) {
            alert("Please enable push notifications");
            return;
        }

        fetch("publish.php", {
            method: "POST",
            body: subscriptionRequestBody(subscription)
        });
    });
}

function subscribe(registration) {
    return registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(applicationServerKey)
    });
}

function contentEncoding() {
    return (PushManager.supportedContentEncodings || ["aesgcm"])[0];
}

function sendSubscription(subscription) {
    return fetch("subscribe.php", {
        method: "POST",
        body: subscriptionRequestBody(subscription)
    });
}

function subscriptionRequestBody(subscription) {
    return JSON.stringify({
        ...subscription.toJSON(),
        contentEncoding: contentEncoding()
    });
}

document.addEventListener("DOMContentLoaded", function() {
    navigator.serviceWorker.register(pushServiceWorkerPath);

    const btnSubscribe = document.getElementById("btnSubscribe");
    btnSubscribe.addEventListener("click", function() {
        checkNotificationPermission()
            .then(() => navigator.serviceWorker.ready)
            .then(subscribe)
            .then(sendSubscription);
    });

    const btnPublish = document.getElementById("btnPublish");
    btnPublish.addEventListener("click", function() {
        navigator.serviceWorker.ready.then(publish);
    });
});
