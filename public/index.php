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
  <title>Push API Example</title>
</head>
<body>
  <button id="btnSubscribe">Subscribe</button>
  <button id="btnPublish">Publish</button>

  <script type="text/javascript" src="./js/app.js"></script>
</body>
</html>
