const pushServiceWorkerPath = "/push_service_worker_example.js";

// VAPIDで使用するサーバ公開鍵 (keys/public_key.txtと一致させる)
const applicationServerKey =
    "BNk_iWsKB1ZCgkRoCsnxb4IwoC-pKGqufg0LfuGbmyDQPOWOxzBCryIN1VwRNaPb0JU5W8ixCqziFSb4_l207xQ";

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

function unsubscribe(registration) {
    return registration.pushManager
        .getSubscription()
        .then(subscription => subscription.unsubscribe());
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

    const btnUnsubscribe = document.getElementById("btnUnsubscribe");
    btnUnsubscribe.addEventListener("click", function() {
        navigator.serviceWorker.ready.then(unsubscribe).catch(e => {
            console.error("Error when unsubscribing the user", e);
        });
    });

    const btnPublish = document.getElementById("btnPublish");
    btnPublish.addEventListener("click", function() {
        navigator.serviceWorker.ready.then(publish);
    });
});
