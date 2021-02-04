self.addEventListener(
    "push",
    event => {
        return event.waitUntil(
            self.registration.showNotification("test", {
                body: "hello world",
                tag: "posket-mall",
                vibrate: [200, 100, 200]
            })
        );
    },
    false
);
