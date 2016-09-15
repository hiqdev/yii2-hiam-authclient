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
 * Class HiamClient.
 * Allows OAuth2 authentication through HIAM server.
 *
 * In order to use HIAM client you must register your application at HIAM server.
 * Used identical to yii2-authclient Oauth2Client.
 *
 * Example application configuration:
 * ```
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => \hiam\authclient\Collection::class,
 *         'clients' => [
 *             'hiam' => [
 *                 'class'        => \hiam\authclient\HiamClient::class,
 *                 'site'         => 'hiam.hipanel.com',
 *                 'clientId'     => 'client_id',
 *                 'clientSecret' => 'client_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ```
 */
class HiamClient extends \yii\authclient\OAuth2
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
     * Inits Urls based on $site.
     */
    public function init()
    {
        parent::init();
        if (!$this->site) {
            $this->site = 'hiam.hipanel.com';
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
        return 'hiam';
    }

    /** {@inheritdoc} */
    protected function defaultTitle()
    {
        return 'hiam';
    }
}
