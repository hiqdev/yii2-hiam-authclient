<?php

namespace hi3a\authclient;

class Collection extends \yii\authclient\Collection
{
    public function getClient ($id = null) {
        if ($id === null) {
            list($id,$dummy) = each($this->getClients());
        };
        return parent::getClient($id);
    }
}
