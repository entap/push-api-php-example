self.addEventListener(
    "push",
    event => {
        return event.waitUntil(
            self.registration.showNotification("test", {
                icon: "(アイコンのURL(パスのみでOK))",
                body: "hello world",
                tag: "posket-mall",
                vibrate: [200, 100, 200]
            })
        );
    },
    false
);
