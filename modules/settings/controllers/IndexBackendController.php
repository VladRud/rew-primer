<?php

namespace app\modules\settings\controllers;

use app\modules\core\components\controllers\BackController;
use app\modules\core\models\GeoCountry;
use app\modules\offer\components\Offer;
use app\modules\settings\forms\FormModel;
use kartik\switchinput\SwitchInput;
use \Yii;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `settings` module
 */
class IndexBackendController extends BackController
{
    public $viewPath = 'app\modules\settings\views\index-backend';

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = Yii::createObject([
            'class' => FormModel::class,
            'keys' => [
                'email' => [
                    'label' => 'Email',
                    'type' => FormModel::TYPE_TEXTINPUT,
                    'options' => ['placeholder' => 'Email'],
                    'rules' => [['required'], ['email']]
                ],
                'site_key' => [
                    'label' => 'reCaptcha Site Key',
                    'type' => FormModel::TYPE_TEXTINPUT,
                    'options' => ['placeholder' => 'reCaptcha Site Key'],
                ],
                'secret_key' => [
                    'label' => 'reCaptcha Secret Key',
                    'type' => FormModel::TYPE_TEXTINPUT,
                    'options' => ['placeholder' => 'reCaptcha Secret Key'],
                ],
                'header_scripts' => [
                    'label' => 'Header Scripts',
                    'type' => FormModel::TYPE_TEXTAREA,
                    'options' => ['placeholder' => 'Header Scripts'],
                ],
                'footer_scripts' => [
                    'label' => 'Footer Scripts',
                    'type' => FormModel::TYPE_TEXTAREA,
                    'options' => ['placeholder' => 'Footer Scripts'],
                ],
                'mandrill_api_key' => [
                    'label' => 'Mandrill API Key',
                    'type' => FormModel::TYPE_TEXTINPUT,
                    'options' => ['placeholder' => 'Mandrill API Key'],
                ],
                'referral_percents' => [
                    'label' => 'Referral Percents',
                    'type' => FormModel::TYPE_TEXTINPUT,
                    'options' => ['placeholder' => 'Referral Percents'],
                    'rules' => [['number', 'min' => 0]]
                ],
                'free_points_register' => [
                    'label' => 'Free Points Amount',
                    'type' => FormModel::TYPE_TEXTINPUT,
                    'options' => ['placeholder' => 'Free Points Amount'],
                    'rules' => [['number', 'min' => 0]]
                ],
                'invite_only_signup' => [
                    'label' => 'Invite Only Signup',
                    'type' => FormModel::TYPE_WIDGET,
                    'widget' => SwitchInput::class,
                    'options' => [
                        'name' => 'inv_only_signup',
                        'inlineLabel' => false,
                        'pluginOptions' => [
                            'handleWidth'=>30,
                        ],
                        'class' => 'self-class'
                    ],
                ],
            ]
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Settings have been updated');
            return $this->refresh();
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionOfferTargeting()
    {
        $model = Yii::createObject([
            'class' => FormModel::class,
            'keys' => [
                Offer::getStorageKeyCountry(Offer::ADWORKMEDIA) => [
                    'format' => FormModel::FORMAT_JSON,
                    'label' => 'adworkmedia',
                    'type' => FormModel::TYPE_DROPDOWN,
                    'options' => [
                        'multiple' => 'multiple',
                        'class' => 'offer-targeting-select',
                    ],
                ],
            ]
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Settings have been updated');
            return $this->refresh();
        }

        return $this->render('offer-targeting', [
            'model' => $model
        ]);
    }

    public function actionGetCountry()
    {
        if (!Yii::$app->request->isAjax) {
            throw new ForbiddenHttpException();
        }

        $collection = GeoCountry::find()
            ->select(['id', 'country_name'])
            ->where(['like', 'country_name', Yii::$app->request->get('q')])
            ->all();

        return Json::encode($collection);
    }
}
