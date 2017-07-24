<?php

namespace app\modules\profile\controllers;

use app\modules\core\models\Transaction;
use app\modules\offer\components\OfferMapper;
use app\modules\core\models\search\TransactionSearch;
use Yii;
use yii\helpers\VarDumper;

class StatsController extends ProfileController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCompletionHistory()
    {
        $offerMapper = new OfferMapper();
        $offerMapper->setOffers(Yii::$app->offerFactory->createAll(false));
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->searchCompletion(
            Yii::$app->request->queryParams,
            Yii::$app->getUser()->getIdentity()
        );

        return $this->render('completion', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'offerMapper' => $offerMapper
        ]);
    }
}