# Push API Example

## Requirement

PHP Extensions

-   gmp
-   curl
-   mbstring
-   openssl

## Usage

Generate keys.

```sh
mkdir keys
openssl ecparam -genkey -name prime256v1 -out keys/private_key.pem
openssl ec -in keys/private_key.pem -pubout -outform DER|tail -c 65|base64|tr -d '=' |tr '/+' '_-' >> keys/public_key.txt
openssl ec -in keys/private_key.pem -outform DER|tail -c +8|head -c 32|base64|tr -d '=' |tr '/+' '_-' >> keys/private_key.txt
```

Start the server.

```sh
docker-compose up
open http://localhost:8080/
```
