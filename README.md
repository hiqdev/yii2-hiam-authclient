Hi3a OAuth2 client for Yii 2
============================

It is the part of [hi3a](https://hiqdev.com/hi3a) and [HiPanel](https://hipanel.com)

It is based on [yiisoft/yii2-authclient](https://github.com/yiisoft/yii2-authclient).

Hi3a is AAA server which provides OAuth2 and ABAC.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist hiqdev/yii2-hi3aclient "*"
```

or add

```json
"hiqdev/yii2-oauth2-server": "*@dev"
```

to the require section of your composer.json.


Quick Start
-----------

To use this extension, add the following code in your application configuration:

```php
    'authClientCollection' => [
        'class' => 'hiqdev\hi3aclient\Collection',
        'clients' => [
            'hi3a' => [
                'class'         => 'common\components\Oauth2Client',
                'site'          => 'sol-hi3a-master.ahnames.com',
                'clientId'      => 'sol-hipanel-master',
                'clientSecret'  => '*******',
            ],
        ],
    ],
```


Usage
-----

Usage is just the same as yii2-authclient.
For more, see [yii2-authclient](https://github.com/yiisoft/yii2-authclient)
