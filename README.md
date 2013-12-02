#Super Simple Auth
=================

Super simple and basic examples for authenticating to a REST API

To use the following examples create a php(e.g index.php) file in the root 
of this directory and copy and paste th code samples below.

#Basic Authentication Example
To Authenticate with just a username and password.

```php
require_once 'Class/BasicAuth.php';

$url = 'endpoint URL';
$username = 'your username';
$password = 'your password';

new BasicAuth($url, $username, $password);
``` 
#Authentication with OAuth.

```php
require_once 'Class/Oauth.php';

$reqUrl  = 'Request Token';
$authUrl = 'Authorize URL';
$accUrl  = 'Access Token URL';
$apiUrl  = 'API URL';
$consKey = 'Consumer Key';
$consSec = 'Consumer Secret';
// This is optional an defaults to 0
// set to 1 to turn on debugging.
$debug = 1;

new Oauthenticate($consKey, $consSec, $apiUrl, $accUrl, $authUrl, $reqUrl, $debug);

```