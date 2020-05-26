<?php

namespace Max13\TelegramSocialite;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Telegram bot username.
     *
     * @var string
     */
    protected $botname;

    /**
     * Set botname.
     *
     * @param  string $botname
     * @return void
     */
    public function setBotname($botname)
    {
        $this->botname = $botname;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        //
    }

    /**
     * Return the login button in HTML.
     *
     * @return string
     */
    public function getButton()
    {
        $botname = $this->botname;
        $callbackUrl = $this->redirectUrl;

        return '<script async src="https://telegram.org/js/telegram-widget.js" data-telegram-login="'.$botname.'" data-size="large" data-userpic="false" data-auth-url="'.$callbackUrl.'" data-request-access="write"></script>';
    }

    /**
     * {@inheritdoc}
     */
    public function redirect()
    {
        return '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />

        <title>Login using Telegram</title>
    </head>
    <body>
        '.$this->getButton().'
    </body>
</html>';
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'        => $user['id'],
            'nickname'  => $user['username'],
            'name'      => trim($user['first_name'].' '.$user['last_name']),
            'avatar'    => $user['photo_url'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function user()
    {
        $validator = Validator::make($this->request->all(), [
            'id'        => 'required|numeric',
            'auth_date' => 'required|date_format:U|before:1 day',
            'hash'      => 'required|size:64',
        ]);

        throw_if($validator->fails(), InvalidArgumentException::class);

        $dataToHash = collect($this->request->except('hash'))
                        ->transform(function ($val, $key) {
                            return "$key=$val";
                        })
                        ->sort()
                        ->join("\n");

        $hash_key = hash('sha256', $this->clientSecret, true);
        $hash_hmac = hash_hmac('sha256', $dataToHash, $hash_key);

        throw_if(
            $this->request->hash !== $hash_hmac,
            InvalidArgumentException::class
        );

        return $this->mapUserToObject($this->request->except(['auth_date', 'hash']));
    }
}
