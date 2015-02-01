Gravatar [![Build Status](https://travis-ci.org/EmanueleMinotto/Gravatar.svg)](https://travis-ci.org/EmanueleMinotto/Gravatar)
====================

PHP library for [gravatar.com](http://www.gravatar.com/) based on [Guzzle 5](http://docs.guzzlephp.org/en/latest/).

## Install
Install Silex using [Composer](http://getcomposer.org/).

Install the Gravatar library adding `emanueleminotto/gravatar` to your composer.json or from CLI:

```
$ composer require emanueleminotto/gravatar
```

## Usage

This library expose 5 APIs:

```php
use EmanueleMinotto\Gravatar\Client;

$client = new Client(/* optional Guzzle HTTP client */);

// user profile
$url = $client->getProfileUrl('user@example.com'); // https://www.gravatar.com/b58996c504c5638798eb6b511e6f49af.json
$qrcode = $client->getProfileUrl('user@example.com', 'qr'); // https://www.gravatar.com/b58996c504c5638798eb6b511e6f49af.qr
$qrcode = $client->getProfileUrl('user@example.com', 'json', [
    'callback' => 'alert',
]); // https://www.gravatar.com/b58996c504c5638798eb6b511e6f49af.qr

$profile = $client->getProfile('user@example.com');
// array(
//   "id" => "b58996c504c5638798eb6b511e6f49af",
//   "hash" => "b58996c504c5638798eb6b511e6f49af",
//   "preferredUsername" => "example user",
// )

// user avatar
$img = $client->getAvatarUrl('user@example.com'); // https://www.gravatar.com/avatar/b58996c504c5638798eb6b511e6f49af.jpg?d=404&r=g&s=80
$img = $client->getAvatarUrl('user@example.com', 150); // https://www.gravatar.com/avatar/b58996c504c5638798eb6b511e6f49af.jpg?d=404&r=g&s=150

$img = $client->getAvatar('user@example.com'); // data URI
$img = $client->getAvatar('user@example.com', 150); // data URI

$exists = $client->exists('user@example.com'); // true
$exists = $client->exists('wrong'); // false
```

## Twig Extension

In this library is included a [Twig](http://twig.sensiolabs.org/) extension to allow a simple integration.

```twig
{% if email is gravatar %} {# this test check if the gravatar exists #}
    
    <a href="{{ email|gravatar_profile_url }}">
        <img src="{{ email|gravatar_url }}" alt="{{ gravatar_profile(email).profileUrl }}"/>
    </a>

{% endif %}
```