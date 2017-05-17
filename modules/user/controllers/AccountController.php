<?php

namespace app\modules\user\controllers;

use app\modules\core\components\controllers\FrontController;
use app\modules\invitation\actions\InvitationRequestAction;
use app\modules\user\components\AuthHandler;
use app\modules\user\controllers\account\ActivateAction;
use app\modules\user\controllers\account\LoginAction;
use app\modules\user\controllers\account\LogoutAction;
use app\modules\user\controllers\account\RecoveryRequestAction;
use app\modules\user\controllers\account\RecoveryResetAction;
use yii\filters\AccessControl;
use app\modules\user\controllers\account\RegisterAction;

class AccountController extends FrontController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'login' => [
                'class' => LoginAction::className(),
                'layout' => '/frontend/main'
            ],
            'back-login' => [
                'class' => LoginAction::className(),
                'layout' => 'login'
            ],
            'logout' => [
                'class' => LogoutAction::className(),
            ],
            'sign-up' => [
                'class' => RegisterAction::className(),
            ],
            'activate' => [
                'class' => ActivateAction::className(),
            ],
            'invitation-request' => [
                'class' => InvitationRequestAction::className(),
            ],
            'recovery-request' => [
                'class' => RecoveryRequestAction::className(),
                'layout' => '/frontend/main'
            ],
            'recovery-reset' => [
                'class' => RecoveryResetAction::className(),
                'layout' => '/frontend/main'
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }
    
    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }
}
