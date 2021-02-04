self.addEventListener("push", event => {
    const textData = event.data.text();
    // const jsonData = event.data.json();

    const title = "Push API Example";

    return event.waitUntil(
        self.registration.showNotification(title, {
            body: textData
            // icon: '/img/icon.png',
            // tag: "posket-mall",
            // vibrate: [200, 100, 200]
        })
    );
});
