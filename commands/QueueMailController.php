<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\modules\user\models\QueueMail;
use yii\helpers\Url;

/**
 * Description of RbacController
 *
 * @author Stableflow
 */
class QueueMailController extends Controller {

    public function actionIndex()
    {
        $model = QueueMail::find()->where(['status' => QueueMail::STATUS_WAIT])->with('user')->limit(10)->all();
        foreach ($model as $key => $value) {
            $this->checkMailId($value);
        }
    }

    public function checkMailId ($mail_params)
    {
        switch ($mail_params->mail_id) {
            case QueueMail::ADMIN_APPROVES_INVITATION_REQUEST :
                break;
            case QueueMail::USER_REQUEST_INVITATION_CODE :
                break;
            case QueueMail::USER_SINGS_UP_WITH_HIS_INVITATION_CODE :
                break;
            case QueueMail::USER_SIGNS_UP_WITH_GENERAL_SIGN_UP_FORM :
                $url = Url::toRoute(['/user/account/activate', 'token' => $mail_params->user->token->code], true);
                $params = ['url' => $url];
                $view = 'invitation_signup_success';
                $subject = 'Activate account';
//                $this->sendEmail($view, $mail_params->user->email, $params, $subject);
//                if($this->sendEmail($view, $mail_params->user->email, $params, $subject)){
//                    $model = QueueMail::findOne($mail_params->id);
//                    $model->status = QueueMail::STATUS_SUCCESS;
//                    $model->update();
//                    echo 'success';
//                }
                $this->sendEmail($view, $mail_params->user, $params, $subject);
//                $mail = $this->render('@app/mail/')
                break;
            case QueueMail::USER_FOLLOWS_CONFIRMATION_LINK_AND_CONFIRMS_HIS_EMAIL :
                break;
            case QueueMail::GUEST_IS_REGISTERED_WITH_REFERRAL_CODE :
                break;
            case QueueMail::ADMIN_BLOCKS_USER :
                break;
            case QueueMail::ADMIN_UNBLOCKS_OR_ACTIVATES_USER :
                break;
            case QueueMail::USER_CREATES_NEW_ORDER :
                break;
            case QueueMail::ADMIN_DECLINES_NEW_ORDER :
                break;
            case QueueMail::ADMIN_APPROVES_NEW_ORDER :
                break;
        }
    }
    public function sendEmail($name_email, $user_to, $params, $subject) {
        $modelMail = new \Mandrill(Yii::$app->params['mandrillApiKey']);
        $message = [
            'subject' => $subject,
            'from_email' => Yii::$app->params['adminEmail'],
            'to' => [['email' => 'rudslawa@yandex.ua', 'name' => $user_to->username]],
            'merge_vars' => [[
                'rcpt' => 'rudslawa@yandex.ua',
                'vars' => [
//                    array(
//                        'name' => 'FIRSTNAME',
//                        'content' => 'Recipient 1 first name'),
//                    array(
//                        'name' => 'LASTNAME',
//                        'content' => 'Last name')
                ]
            ]],
        ];
        $template_name = 'main_template';
        $template_content = [
//            [
//                'name' => 'main',
//                'content' => 'Hi, thanks for signing up.'
//            ]
        ];
        $response = $modelMail->messages->sendTemplate($template_name, $template_content, $message);
        print_r($response);
        return true;
    }
//
//    public function sendEmail($name_email, $send_to, $params, $subject)
//    {
//        $mail = Yii::$app->mailer
//            ->compose($name_email, $params)
//            ->setTo($send_to)
//            ->setSubject($subject)
//            ->send();
//        return $mail;
//    }

}