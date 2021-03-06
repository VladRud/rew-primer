<?php

namespace app\modules\user\controllers\account;

use app\modules\user\models\User;
use Yii;
use yii\base\Action;
use app\modules\user\forms\LoginForm;

/**
 * Description of LoginAction
 *
 * @author Stableflow
 */
class LoginAction extends Action
{
    public $layout;

    public function run()
    {
        $form = new LoginForm();

        if ($form->load(Yii::$app->request->post())) {

            if ($form->validate() && Yii::$app->authenticationManager->login($form, Yii::$app->getRequest())) {
                $user = Yii::$app->getUser()->getIdentity();
                return $user->role == User::ROLE_ADMIN ?
                    $this->controller->redirect(['/dashboard/index-backend/index']) :
                    $this->controller->redirect([$user->getReturnUrl()]);
            } else {
                if ($form->hasErrors('password')) {
//                    $errors = $form->getErrors('password');
//                        Yii::$app->session->setFlash('error', $errors[0]);
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

}
