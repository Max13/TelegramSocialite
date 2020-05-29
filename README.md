
[![Style Status](https://github.styleci.io/repos/266998759/shield?branch=master)](https://github.styleci.io/repos/266998759) [![Latest Stable Version](https://poser.pugx.org/max13/telegram-socialite/v)](//packagist.org/packages/max13/telegram-socialite)


# TelegramSocialite

Telegram provider for Laravel Socialite


## Install

```
composer require max13/telegram-socialite
```


## Configuration

First of all, you must create a bot by contacting [@BotFather](http://t.me/BotFather) (https://core.telegram.org/bots#6-botfather)

> Don't forget to set your website URL using `/setdomain`

Then, as required by [Laravel Socialite](https://laravel.com/docs/socialite#configuration), you need to add your bot's configuration to `config/services.php`. The bot username is required, `client_id` must be `null`. The provider will also ask permission for the bot to write to the user.

```php
'telegram' => [
    'client_id' => null,
    'client_secret' => env('TELEGRAM_TOKEN'),
    'redirect' => '/login/telegram/callback',
]
```


## Usage

Now, Telegram is technically using `OAuth`, but not the usual workflow.

First or all, you **must** add a javascript to your page, anywhere you want (in the `<head>` or bottom of page) with this snippet:

```php
{!! Socialite::driver('telegram')->getScript() !!}
```

You also **must** call `_TWidgetLogin.auth()` on click on your login button, which will open a popup showing the Telegram OAuth access request. Because of browser's security, you can't automatically call this, it must be called as a result of a user's action.

If the user **accept** the access request, the browser is redirected to your `services.telegram.redirect` config key and you will have access to the logged-in user data the classic `Socialite` way:

```php
Socialite::driver('telegram')->user();
```

If the user **declines**, an `InvalidArgumentException` exception will be thrown.

Using `Socialite::driver('telegram')->redirect()` will abort the connection with a `404` error.

If you want to see the Telegram Widget configuration page: https://core.telegram.org/widgets/login


## Issues

https://github.com/Max13/TelegramSocialite/issues
