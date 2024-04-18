# OneSend API SDK for PHP

## Requirements
<hr>

To use the OneSend PHP SDK the following things are required:
* A free [OneSend account](https://onesend.de) (to actually send messages you're required to add balance to your account)
* PHP >= 8.2
* Optional: A [PSR-18 compliant HTTP Client](https://docs.php-http.org/en/latest/httplug/users.html), by default it will use the [Symfony Http Client](https://docs.php-http.org/en/latest/clients/symfony-client.html)


## Installation
<hr>

### Using Composer
The best way to install the OneSend PHP SDK is by using [Composer](http://getcomposer.org/). You can require it with the following command:
```
composer require onesend-gmbh/onesend-php-sdk
```

## Usage
<hr>

Initialise the SDK by passing the Api Key from your [Project Dashboard](https://onesend.de/en/user/dashboard).
```php
$oneSend = new \OnesendGmbh\OnesendPhpSdk\OneSendApi('YOUR KEY HERE');
```

Optionally you can also pass a PSR-18 compliant Client as second argument if you want to modify timeouts/retry behavior or for Testing.

Using the SDK you can now access the following endpoints:

| API                                                          | Resource       | Code                    | Link to Endpoint File                                                                                                    |
|--------------------------------------------------------------|----------------|-------------------------|--------------------------------------------------------------------------------------------------------------------------|
| [Short Messages API](https://docs.onesend.de/short_messages) | Short Messages | $oneSend->shortMessages | [ShortMessageEndpoint](https://github.com/onesend-gmbh/onesend-php-sdk/blob/main/src/Endpoints/ShortMessageEndpoint.php) |

You can find our full documentation [here](https://docs.onesend.de).

## Short Messages
<hr>

### Sending Short Messages (SMS)
[Create Short Message reference](https://docs.onesend.de/short_messages#create-a-short-message)
```php
$shortMessage = $oneSend->shortMessages->send([
    'to' => '+4915730955123',
    'from' => 'TEST',
    'message' => 'THIS IS A TEST',
]);
```

This will create a [ShortMessage Resource](https://github.com/onesend-gmbh/onesend-php-sdk/blob/main/src/Resources/ShortMessage.php) with a message ID `$shortMessage->getId()` you can and some other information about the sent short message.


## Testing
<hr>

By default, the SDK will set the [Symfony Http Client](https://docs.php-http.org/en/latest/clients/symfony-client.html) as HTTP Client on initialisation, meaning should you not Mock calls to the SDK, it WILL send request to our service and your tests will most likely fail. <br>
If you don't want to (or can't) mock the calls to the SDK you can also replace the HTTP Client with a Mock Client ([PHP HTTP Mock Client](https://docs.php-http.org/en/latest/clients/mock-client.html) for example) by passing it as the second constructor argument:
```php
$mockClient = new Http\Mock\Client();
$oneSend = new \OnesendGmbh\OnesendPhpSdk\OneSendApi('I am a Test', $mockClient);
```
This will replace the default Http Client and will enable you to intercept and validate requests made by the SDK as well as mock responses with the desired outcome.<br>
To see the expected responses please consult our [API docs](https://docs.onesend.de).
