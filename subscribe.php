<?php
if ($_REQUEST['subscribe']) {
    require_once __DIR__ . '/vendor/autoload.php';

    $subscriptions = new \Entap\WebPush\Repositories\FileSubscriptionRepository();
    $subscriptions->save($_POST);
    echo '{ "status": "ok" }';
    die();
} ?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <button id="btnSubscribe">Subscribe</button>

  <script>
    const btn = document.getElementById('btnSubscribe');
    const serverPublicKey = '';
    const options = {
      userVisibleOnly: true,
      applicationServerKey: serverPublicKey // VAPIDで使用するサーバ公開鍵
    }

    navigator.serviceWorker.register('./service-worker-web-push.js');

    navigator.serviceWorker.ready.then(registration => {
        return registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: serverPublicKey // VAPIDで使用するサーバ公開鍵
        }).then(subscription => {
        });
    });

    btn.addEventListener('click', function() {
      serviceWorkerRegistration.pushManager.subscribe(options)
      .then(function(subscription) {
        const endpoint = subscription.endpoint; // エンドポイントのURL
        const publicKey = encodeBase64URL(subscription.getKey('p256dh')); // クライアント公開鍵
        const authSecret = encodeBase64URL(subscription.getKey('auth')); // auth_secret
        const contentEncoding = 'supportedContentEncodings' in PushManager ?
            (PushManager.supportedContentEncodings.includes('aes128gcm') ? 'aes128gcm' : 'aesgcm') :
            'aesgcm';

        fetch('/test_subscribe.php?subscribe=1', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Content-Encoding': contentEncoding,
          },
          body: JSON.stringify({ endpoint, publicKey, authSecret })
        });
      });
    });

</script>
</body>
</html>
