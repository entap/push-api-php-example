# Push API Example

## Requirement

PHP Extensions

-   gmp
-   curl
-   mbstring
-   openssl

gmp を入れないと実行が遅くなってタイムアウトするが、たまに送れるので注意。

## Usage

### Generate Keys

キーはサービスごとに作り直すこと。ローカルで試すだけなら省略していい。

```sh
openssl ecparam -genkey -name prime256v1 -out keys/private_key.pem
openssl ec -in keys/private_key.pem -pubout -outform DER|tail -c 65|base64|tr -d '=' |tr '/+' '_-' >> keys/public_key.txt
openssl ec -in keys/private_key.pem -outform DER|tail -c +8|head -c 32|base64|tr -d '=' |tr '/+' '_-' >> keys/private_key.txt
```

`public/js/app.js` の `applicationServerKey` を `keys/public_key.txt` の値に書き換える。

## Testing

Start the server.

```sh
composer install
docker-compose up
open http://localhost:8080/
```

ページを開いたら、Subscribe で通知を許可してから Publish を押す。
