Gravatar
========

[![Build Status](https://img.shields.io/travis/EmanueleMinotto/Gravatar.svg?style=flat)](https://travis-ci.org/EmanueleMinotto/Gravatar)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/9d962121-4ec7-4b65-bd06-62299424a180.svg?style=flat)](https://insight.sensiolabs.com/projects/9d962121-4ec7-4b65-bd06-62299424a180)
[![Coverage Status](https://img.shields.io/coveralls/EmanueleMinotto/Gravatar.svg?style=flat)](https://coveralls.io/r/EmanueleMinotto/Gravatar)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/EmanueleMinotto/Gravatar.svg?style=flat)](https://scrutinizer-ci.com/g/EmanueleMinotto/Gravatar/)
[![Total Downloads](https://img.shields.io/packagist/dt/emanueleminotto/gravatar.svg?style=flat)](https://packagist.org/packages/emanueleminotto/gravatar)

PHP library for [gravatar.com](http://www.gravatar.com/) based on [Guzzle 5](http://docs.guzzlephp.org/en/latest/).

API: [emanueleminotto.github.io/Gravatar](http://emanueleminotto.github.io/Gravatar/)

## Install
Install Silex using [Composer](http://getcomposer.org/).

Install the Gravatar library adding `emanueleminotto/gravatar` to your composer.json or from CLI:

```
$ composer require emanueleminotto/gravatar
```

## Usage

This library exposes 5 APIs:

```php
use EmanueleMinotto\Gravatar\Client;

$client = new Client(/* optional Guzzle HTTP client */);

// user profile
$url = $client->getProfileUrl('user@example.com'); // https://www.gravatar.com/b58996c504c5638798eb6b511e6f49af.json
$qrcode = $client->getProfileUrl('user@example.com', 'qr'); // https://www.gravatar.com/b58996c504c5638798eb6b511e6f49af.qr
$qrcode = $client->getProfileUrl('user@example.com', 'json', [
    'callback' => 'alert',
]); // https://www.gravatar.com/b58996c504c5638798eb6b511e6f49af.json?callback=alert

$profile = $client->getProfile('user@example.com');
// array(
//   "id" => "b58996c504c5638798eb6b511e6f49af",
//   "hash" => "b58996c504c5638798eb6b511e6f49af",
//   "preferredUsername" => "example user",
//   ...
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

In this library there's included a [Twig](http://twig.sensiolabs.org/) extension to allow a simple integration with frameworks.

```twig
{% if email is gravatar %} {# this test check if the gravatar exists #}
    <a href="{{ email|gravatar_profile_url }}">
        <img
            src="{{ email|gravatar_url }}"
            alt="{{ gravatar_profile(email).profileUrl }}"
        />
    </a>
{% endif %}
```
