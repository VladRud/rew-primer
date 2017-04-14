<?php

namespace app\modules\user\controllers\account;

use Yii;
use yii\base\Action;
use app\modules\user\forms\LoginForm;
use app\modules\setting\helpers\SettingHelper;
use yii\base\ErrorException;

/**
 * Description of LoginAction
 *
 * @author Stableflow
 */
class LoginAction extends Action {

    public $layout;

    public function run() {
        if (Yii::$app->user->isGuest) {

            $form = new LoginForm();

            if ($form->load(Yii::$app->request->post())) {

                if ($form->validate() && Yii::$app->authenticationManager->login($form, Yii::$app->getUser(), Yii::$app->getRequest())) {
                    return $this->controller->redirect([Yii::$app->user->identity->returnUrl]);
                } else {
                    if($form->hasErrors('password')){
                        $errors = $form->getErrors('password');
                        Yii::$app->session->setFlash('error', $errors[0]);
                    }
                }

            }
            if (!empty($this->layout)) {
                $this->controller->layout = $this->layout;
            }

            return $this->controller->render($this->id, [
                        'model' => $form
            ]);
        }
        return $this->controller->redirect(Yii::$app->user->returnUrl);
    }

}
