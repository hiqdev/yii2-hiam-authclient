<?php

namespace hiam\authclient;

class Collection extends \yii\authclient\Collection
{
    /**
     * Gets first client by default
     */
    public function getClient ($id = null)
    {
        if ($id === null) {
            list($id,$dummy) = each($this->getClients());
        };
        return parent::getClient($id);
    }

    /**
     * Finds first active (connected) client
     */
    public function getActiveClient ()
    {
        foreach ($this->getClients() as $id => $client) {
            $token = $client->getAccessToken();
            if ($token) return $client;
        };
        return null;
    }
}
