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

use Exception;
use Psr\Log\LoggerInterface;
use Yii;
use yii\helpers\Html;

class AuthAction extends \yii\authclient\AuthAction
{
    private LoggerInterface $log;

    public function __construct($id, $controller, LoggerInterface $log, $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->log = $log;
    }

    public function run()
    {
        try {
            return parent::run();
        } catch (Exception $e) {
            if ($e->getMessage() !== 'Invalid auth state parameter.') {
                Yii::$app->getResponse()->setStatusCode(500);
                return Html::encode($e->getMessage());
            }

            return $this->handleInvalidAuthState($e);
        }
    }

    private function handleInvalidAuthState(Exception $exception)
    {
        if (!empty(Yii::$app->user->id)) {
            return $this->redirectSuccess();
        }

        $errorMessage = sprintf('Failed to exchange the auth code to the access token: %s', $exception->getMessage());
        $this->log->error($errorMessage, ['exception' => $exception]);

        if (YII_ENV !== 'prod') {
            echo $errorMessage;
        }

        return '';
    }
}
