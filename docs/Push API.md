# Push API

## Work Flow

1. ボタンを押したら通知を許可するかどうか確認する
2. ページを開いたら通知を受け取るサービスワーカーを登録する
3. サービスワーカーを登録したら、通知を購読するために、購読情報を送信する
4. 通知を配信したら、サービスワーカーから Notification API を呼び出して通知を表示する

## References

-   [PHP の Push API パッケージ](https://github.com/web-push-libs/web-push-php)
-   [Push API パッケージの使用例](https://github.com/Minishlink/web-push-php-example)
