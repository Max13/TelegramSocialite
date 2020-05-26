[![Style Status](https://github.styleci.io/repos/266998759/shield?branch=master)](https://github.styleci.io/repos/266998759)


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
    'botname' => 'mysuper_bot',
    'client_id' => null,
    'client_secret' => env('TELEGRAM_TOKEN'),
    'redirect' => '/login/telegram/callback',
]
```


## Usage

Now, Telegram is technically using `OAuth`, but not the usual workflow. To show the login button, you have to load a `<script>` tag where you want the button to be (the script loads an iframe containing the necessary) using:

```php
Socialite::driver('telegram')->getButton();
```

Anyway, if you use `Socialite::driver('telegram')->redirect()`, the provider still has your back and will simply show the button alone on an empty page.

When the user clicks on the button, `a new window is opened` and when the user is logged-in, the script will redirect on your `redirect` URL, with a payload in the query. Then you access the logged-in user data the classic `Socialite` way:

```php
Socialite::driver('telegram')->user();
```

If you want to see the Telegram Widget configuration page: https://core.telegram.org/widgets/login


## Issues

https://github.com/Max13/TelegramSocialite/issues
