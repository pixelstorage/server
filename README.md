# PixelStorage

PixelStorage is an open source image hosting service. It was designed to server image hostings and manipulations in a single place.

## Features
 * [The client](https://github.com/pixelstorage/php-client) is developer friendly.
 * The image ID size is predicitble. It's a 70 letter, which is compound by two hashes.
 * Secure
    * Only clients with credentials can issue an upload URL.
    * All maniputalions URLs are signed.
 * Easy of use
    * It can be uploaded directly from backend to backend.
    * The backend can share securely an 'upload url' to their clients.
 * Cache friendly
    * Images never expires, because updates are prohibited.
       * Instead of allowing updates, the clients would have to issue a new upload and keep track of the new image id.
    * It sets the headers for allowing any CDN to cache the responses forever.
    
## Installation

```bash
git clone https://github.com/pixelstorage/server
composer install
cp .env.example .env
# Edit .env
php artision migrate
php artision make:client # Copy the credentials to your client.
```
