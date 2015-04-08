<?php

namespace hi3a\authclient;

/**
 * hi3a allows authentication via hi3a OAuth2.
 *
 * In order to use hi3a you must register your application at <https://hi3a.hipanel.com/>.
 *
 * Example application configuration:
 *
 * ~~~
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'hi3a\authclient\Collection',
 *         'clients' => [
 *             'hi3a' => [
 *                 'class'        => 'hiqdev\hi3aClient\Oauth2Client',
 *                 'site'         => 'hi3a.hipanel.com',
 *                 'clientId'     => 'client_id',
 *                 'clientSecret' => 'client_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ~~~
 */
class Oauth2Client extends \yii\authclient\OAuth2
{
    /** site for urls generation */
    public $site;

    public function buildUrl ($path,array $params = []) {
        $url = $this->site.'/'.$path;
        return $params ? $this->composeUrl($url,$params) : $url;
    }

    /** inits Urls based on $site */
    public function init () {
        parent::init();
        if (!$this->site) {
            $this->site = 'hi3a.hipanel.com';
        };
        if (strpos($this->site, '://') === false) {
            $this->site = 'https://'.$this->site;
        };
        $defaults = [
            'authUrl'       => 'oauth/authorize',
            'tokenUrl'      => 'oauth/token',
            'apiBaseUrl'    => 'api',
        ];
        foreach ($defaults as $k => $v) {
            if (!$this->{$k}) {
                $this->{$k} = $this->buildUrl($v);
            };
        };
    }

    /** @inheritdoc */
    protected function initUserAttributes () {
        return $this->getAccessToken()->getParam('user_attributes');
    }

    /** @inheritdoc */
    protected function apiInternal ($accessToken, $url, $method, array $params, array $headers) {
        if (!isset($params['format'])) {
            $params['format'] = 'json';
        }
        $params['oauth_token'] = $accessToken->getToken();

        return $this->sendRequest($method, $url, $params, $headers);
    }

    /** @inheritdoc */
    protected function defaultName  () { return 'hi3a'; }

    /** @inheritdoc */
    protected function defaultTitle () { return 'hi3a'; }
}
