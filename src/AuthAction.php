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

use Yii;
use yii\helpers\Html;

class AuthAction extends \yii\authclient\AuthAction
{
    public function run()
    {
        try {
            return parent::run();
        } catch (\Exception $e) {
            $response = Yii::$app->getResponse();

            $isLoggedIn = !empty(Yii::$app->user->id);
            if ($e->getMessage() === 'Invalid auth state parameter.' && $isLoggedIn) {
                return $this->redirectSuccess();
            }
            $response->setStatusCode(500);

            return Html::encode($e->getMessage());
        }
    }
}
