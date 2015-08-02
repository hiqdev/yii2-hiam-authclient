<?php

/*
 * OAuth2 client for yii2 to login through HIAM server
 *
 * @link      https://github.com/hiqdev/yii2-hiam-authclient
 * @package   yii2-hiam-authclient
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015, HiQDev (https://hiqdev.com/)
 */

namespace hiam\authclient;

class Collection extends \yii\authclient\Collection
{
    /**
     * Gets first client by default.
     */
    public function getClient($id = null)
    {
        if ($id === null) {
            list($id, $dummy) = each($this->getClients());
        };

        return parent::getClient($id);
    }

    /**
     * Finds first active (connected) client.
     */
    public function getActiveClient()
    {
        foreach ($this->getClients() as $id => $client) {
            $token = $client->getAccessToken();
            if ($token) {
                return $client;
            }
        };

        return;
    }
}
