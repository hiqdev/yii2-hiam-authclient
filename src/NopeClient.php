<?php

/*
 * OAuth2 client for yii2 to login with HIAM server
 *
 * @link      https://github.com/hiqdev/yii2-hiam-authclient
 * @package   yii2-hiam-authclient
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hiam\authclient;

/**
 * Class NopeClient.
 * Allows OAuth2 authentication through Nope server.
 *
 * In order to use NOPE client you must register your application at NOPE server.
 * Used identical to yii2-authclient Oauth2Client.
 *
 * Example application configuration:
 * ```
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => \hiam\authclient\Collection::class,
 *         'clients' => [
 *             'nope' => [
 *                 'class'        => \hiam\authclient\NopeClient::class,
 *                 'site'         => 'nope.somewhere.com',
 *                 'clientId'     => 'client_id',
 *                 'clientSecret' => 'client_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ```
 */
class NopeClient extends \yii\authclient\OAuth2
{
    /**
     * Site for urls generation.
     */
    public $site;

    public function buildUrl($path, array $params = [])
    {
        $url = $this->site . '/' . $path;

        return $params ? $this->composeUrl($url, $params) : $url;
    }

    /**
     * {@inheritdoc}
     */
    public function buildAuthUrl(array $params = [])
    {
        $params['language'] = \Yii::$app->language;

        return parent::buildAuthUrl($params);
    }

    /**
     * Inits Urls based on $site.
     */
    public function init()
    {
        parent::init();
        if (!$this->site) {
            throw new \Exception('Nope site must not be empty!');
        }
        if (strpos($this->site, '://') === false) {
            $this->site = 'https://' . $this->site;
        }
        $defaults = [
            'authUrl' => 'oauth/authorize',
            'tokenUrl' => 'oauth/token',
            'apiBaseUrl' => 'api',
        ];
        foreach ($defaults as $k => $v) {
            if (!$this->{$k}) {
                $this->{$k} = $this->buildUrl($v);
            }
        }
    }

    /** {@inheritdoc} */
    protected function initUserAttributes()
    {
        return $this->getAccessToken()->getParam('user_attributes');
    }

    /** {@inheritdoc} */
    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        if (!isset($params['format'])) {
            $params['format'] = 'json';
        }
        $params['oauth_token'] = $accessToken->getToken();

        return $this->sendRequest($method, $url, $params, $headers);
    }

    /** {@inheritdoc} */
    protected function defaultName()
    {
        return 'nope';
    }

    /** {@inheritdoc} */
    protected function defaultTitle()
    {
        return 'nope';
    }

    /**
     * {@inheritdoc}
     */
    public function setState($key, $value)
    {
        return parent::setState($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getState($key)
    {
        return parent::getState($key);
    }

    /**
     * {@inheritdoc}
     */
    public function removeState($key)
    {
        return parent::removeState($key);
    }
}
