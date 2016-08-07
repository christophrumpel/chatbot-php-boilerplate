# PHP Chatbot Boilerplate


This package makes it simple to start building a chatbot in PHP.

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/thephpleague/oauth2-client/blob/master/LICENSE)

---

If you want to start building a chatbot in PHP, then this is a perfect start. It includes everything you need to know to
 connect you application to a messenger (currently on Facebook Messenger support). You will find simple examples to 
 reply to the messenger.
 
Additionally this boilerplate supports bot platforms like [api.ai](http://api.ai) and [wit.ai](http://wit.ai) (comings 
 oon) too. This will help you to process and understand the user's message and intent.

This package uses [PSR-1][] and [PSR-2][],  If you notice compliance oversights, please send a patch via pull request.

## Requirements

* >= PHP 7

## Covered

* Create a FB Messenger app
* Create a FB Page
* Setup the PHP chatbot boilerplate
* Connect the Messenger app to the FB page
* Create a webhook for the messenger app

## Not covered

* How to use wit.ai
* How to user api.ai

## Installation

### Create a FB page

First login to Facebook and [create](https://www.facebook.com/pages/create) a Facebook page. The doesn't need to be public.
Choose the settings that fits best to your bot, but for testing it is not important.

### Create a FB Messenger app

Go to the [developer's app page](https://developers.facebook.com/apps/). Click "Add a New App" and
 fill the basic app fields.

** Screenshot cra a new app ai **

On the "Product Setup" page choose Messenger and click "Get Started". Now we need to create a token to let our app 
access our Facebook page. Select the created page, grant permissions and copy the generated token. We need that one later.

### Setup the PHP chatbot boi






```php
$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => 'demoapp',    // The client ID assigned to you by the provider
    'clientSecret'            => 'demopass',   // The client password assigned to you by the provider
    'redirectUri'             => 'http://example.com/your-redirect-url/',
    'urlAuthorize'            => 'http://brentertainment.com/oauth2/lockdin/authorize',
    'urlAccessToken'          => 'http://brentertainment.com/oauth2/lockdin/token',
    'urlResourceOwnerDetails' => 'http://brentertainment.com/oauth2/lockdin/resource'
]);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    // (e.g. state).
    $authorizationUrl = $provider->getAuthorizationUrl();

    // Get the state generated for you and store it to the session.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    try {

        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo $accessToken->getToken() . "\n";
        echo $accessToken->getRefreshToken() . "\n";
        echo $accessToken->getExpires() . "\n";
        echo ($accessToken->hasExpired() ? 'expired' : 'not expired') . "\n";

        // Using the access token, we may look up details about the
        // resource owner.
        $resourceOwner = $provider->getResourceOwner($accessToken);

        var_export($resourceOwner->toArray());

        // The provider provides a way to get an authenticated API request for
        // the service, using the access token; it returns an object conforming
        // to Psr\Http\Message\RequestInterface.
        $request = $provider->getAuthenticatedRequest(
            'GET',
            'http://brentertainment.com/oauth2/lockdin/resource',
            $accessToken
        );

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());

    }

}
```

### Refreshing a Token

Once your application is authorized, you can refresh an expired token using a refresh token rather than going through the entire process of obtaining a brand new token. To do so, simply reuse this refresh token from your data store to request a refresh.

_This example uses [Brent Shaffer's](https://github.com/bshaffer) demo OAuth 2.0 application named **Lock'd In**. See authorization code example above, for more details._

```php
$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => 'demoapp',    // The client ID assigned to you by the provider
    'clientSecret'            => 'demopass',   // The client password assigned to you by the provider
    'redirectUri'             => 'http://example.com/your-redirect-url/',
    'urlAuthorize'            => 'http://brentertainment.com/oauth2/lockdin/authorize',
    'urlAccessToken'          => 'http://brentertainment.com/oauth2/lockdin/token',
    'urlResourceOwnerDetails' => 'http://brentertainment.com/oauth2/lockdin/resource'
]);

$existingAccessToken = getAccessTokenFromYourDataStore();

if ($existingAccessToken->hasExpired()) {
    $newAccessToken = $provider->getAccessToken('refresh_token', [
        'refresh_token' => $existingAccessToken->getRefreshToken()
    ]);

    // Purge old access token and store new access token to your data store.
}
```

### Resource Owner Password Credentials Grant

Some service providers allow you to skip the authorization code step to exchange a user's credentials (username and password) for an access token. This is referred to as the "resource owner password credentials" grant type.

According to [section 1.3.3](http://tools.ietf.org/html/rfc6749#section-1.3.3) of the OAuth 2.0 standard (emphasis added):

> The credentials **should only be used when there is a high degree of trust**
> between the resource owner and the client (e.g., the client is part of the
> device operating system or a highly privileged application), and when other
> authorization grant types are not available (such as an authorization code).

**We do not advise using this grant type if the service provider supports the authorization code grant type (see above), as this reinforces the [password anti-pattern](https://agentile.com/the-password-anti-pattern) by allowing users to think it's okay to trust third-party applications with their usernames and passwords.**

That said, there are use-cases where the resource owner password credentials grant is acceptable and useful. Here's an example using it with [Brent Shaffer's](https://github.com/bshaffer) demo OAuth 2.0 application named **Lock'd In**. See authorization code example above, for more details about the Lock'd In demo application.

``` php
$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => 'demoapp',    // The client ID assigned to you by the provider
    'clientSecret'            => 'demopass',   // The client password assigned to you by the provider
    'redirectUri'             => 'http://example.com/your-redirect-url/',
    'urlAuthorize'            => 'http://brentertainment.com/oauth2/lockdin/authorize',
    'urlAccessToken'          => 'http://brentertainment.com/oauth2/lockdin/token',
    'urlResourceOwnerDetails' => 'http://brentertainment.com/oauth2/lockdin/resource'
]);

try {

    // Try to get an access token using the resource owner password credentials grant.
    $accessToken = $provider->getAccessToken('password', [
        'username' => 'demouser',
        'password' => 'testpass'
    ]);

} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

    // Failed to get the access token
    exit($e->getMessage());

}
```

### Client Credentials Grant

When your application is acting on its own behalf to access resources it controls/owns in a service provider, it may use the client credentials grant type. This is best used when the credentials for your application are stored privately and never exposed (e.g. through the web browser, etc.) to end-users. This grant type functions similarly to the resource owner password credentials grant type, but it does not request a user's username or password. It uses only the client ID and secret issued to your client by the service provider.

Unlike earlier examples, the following does not work against a functioning demo service provider. It is provided for the sake of example only.

``` php
// Note: the GenericProvider requires the `urlAuthorize` option, even though
// it's not used in the OAuth 2.0 client credentials grant type.

$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => 'XXXXXX',    // The client ID assigned to you by the provider
    'clientSecret'            => 'XXXXXX',    // The client password assigned to you by the provider
    'redirectUri'             => 'http://my.example.com/your-redirect-url/',
    'urlAuthorize'            => 'http://service.example.com/authorize',
    'urlAccessToken'          => 'http://service.example.com/token',
    'urlResourceOwnerDetails' => 'http://service.example.com/resource'
]);

try {

    // Try to get an access token using the client credentials grant.
    $accessToken = $provider->getAccessToken('client_credentials');

} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

    // Failed to get the access token
    exit($e->getMessage());

}
```

### Using a proxy

It is possible to use a proxy to debug HTTP calls made to a provider. All you need to do is set the `proxy` and `verify` options when creating your Provider instance. Make sure you enable SSL proxying in your proxy.

``` php
$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => 'XXXXXX',    // The client ID assigned to you by the provider
    'clientSecret'            => 'XXXXXX',    // The client password assigned to you by the provider
    'redirectUri'             => 'http://my.example.com/your-redirect-url/',
    'urlAuthorize'            => 'http://service.example.com/authorize',
    'urlAccessToken'          => 'http://service.example.com/token',
    'urlResourceOwnerDetails' => 'http://service.example.com/resource',
    'proxy'                   => '192.168.0.1:8888',
    'verify'                  => false
]);
```

## Install

Via Composer

``` bash
$ composer require league/oauth2-client
```

## Contributing

Please see [CONTRIBUTING](https://github.com/thephpleague/oauth2-client/blob/master/CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](https://github.com/thephpleague/oauth2-client/blob/master/LICENSE) for more information.


[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md
[PSR-7]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md