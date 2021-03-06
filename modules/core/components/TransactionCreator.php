<?php

namespace app\modules\core\components;

use app\modules\core\models\RefTransactionOffer;
use app\modules\core\models\RefTransactionReferral;
use app\modules\core\models\Transaction;
use app\modules\user\models\User;
use Yii;
use yii\base\ErrorException;
use yii\helpers\Json;

/**
 * Class TransactionCreator
 * @package app\modules\core\components
 */
class TransactionCreator
{
    /**
     * @param $status
     * @param $amount
     * @param User $user
     * @param null $userIP
     * @param $offerID
     * @param null $leadID
     * @param null $campaignID
     * @param null $campaignName
     * @param null $description
     * @param null $params
     * @return bool
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function offerIncome($status, $amount, User $user, $userIP = null, $offerID, $leadID = null, $campaignID = null,
                          $campaignName = null, $description = null, $params = null)
    {
        if (!empty($leadID) && $transactionOffer = RefTransactionOffer::find()->with(['transaction'])->lead($leadID, $offerID)->one()) {
            throw new ErrorException('Transaction lead already exists: ' . $transactionOffer->transaction->id);
        }

        $transactionDB = Yii::$app->db->beginTransaction();
        try {
            $transactionModel = new Transaction();
            $transactionModel->type = Transaction::TYPE_OFFER_INCOME;
            $transactionModel->status = $status;
            $transactionModel->amount = $amount;
            $transactionModel->user_id = $user->id;
            $transactionModel->ip = $userIP;
            $transactionModel->description = $description;
            $transactionModel->params = $params;
            if (!$transactionModel->save()) {
                throw new ErrorException('Could not save ' . Transaction::class . PHP_EOL . Json::encode($transactionModel->errors));
            }

            $refTransactionOfferModel = new RefTransactionOffer();
            $refTransactionOfferModel->transaction_id = $transactionModel->id;
            $refTransactionOfferModel->offer_id = $offerID;
            $refTransactionOfferModel->lead_id = $leadID;
            $refTransactionOfferModel->campaign_id = $campaignID;
            $refTransactionOfferModel->campaign_name = $campaignName;
            if (!$refTransactionOfferModel->save()) {
                throw new ErrorException('Could not save ' . RefTransactionOffer::class . PHP_EOL . Json::encode($refTransactionOfferModel->errors));
            }

            $transactionDB->commit();
            return true;
        } catch (\Exception $e) {
            $transactionDB->rollBack();
            throw $e;
        }
    }

    /**
     * @param $status
     * @param $amount
     * @param User $user
     * @param User $referral
     * @param null $userIP
     * @param null $description
     * @param null $params
     * @return bool
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function referralIncome($status, $amount, User $user, $userIP = null, User $referral, $description = null, $params = null)
    {
        $transactionDB = Yii::$app->db->beginTransaction();
        try {
            $transactionModel = new Transaction();
            $transactionModel->type = Transaction::TYPE_REFERRAL_BONUS;
            $transactionModel->status = $status;
            $transactionModel->amount = $amount;
            $transactionModel->user_id = $user->id;
            $transactionModel->ip = $userIP;
            $transactionModel->description = $description;
            $transactionModel->params = $params;
            if (!$transactionModel->save()) {
                throw new ErrorException('Could not save ' . Transaction::class . PHP_EOL . Json::encode($transactionModel->errors));
            }

            $refTransactionReferralModel = new RefTransactionReferral();
            $refTransactionReferralModel->transaction_id = $transactionModel->id;
            $refTransactionReferralModel->user_id = $referral->id;
            if (!$refTransactionReferralModel->save()) {
                throw new ErrorException('Could not save ' . RefTransactionReferral::class . PHP_EOL . Json::encode($refTransactionReferralModel->errors));
            }

            $transactionDB->commit();
            return true;
        } catch (\Exception $e) {
            $transactionDB->rollBack();
            throw $e;
        }
    }

    /**
     * @param $status
     * @param $amount
     * @param User $user
     * @param null $userIP
     * @param null $description
     * @param null $params
     * @return bool
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function redeem($status, $amount, User $user, $userIP = null, $description = null, $params = null)
    {
        $transactionDB = Yii::$app->db->beginTransaction();
        try {
            $transactionModel = new Transaction();
            $transactionModel->type = Transaction::TYPE_REDEEM;
            $transactionModel->status = $status;
            $transactionModel->amount = $amount;
            $transactionModel->user_id = $user->id;
            $transactionModel->ip = $userIP;
            $transactionModel->description = $description;
            $transactionModel->params = $params;
            if (!$transactionModel->save()) {
                throw new ErrorException('Could not save ' . Transaction::class . PHP_EOL . Json::encode($transactionModel->errors));
            }

            $transactionDB->commit();
            return true;
        } catch (\Exception $e) {
            $transactionDB->rollBack();
            throw $e;
        }
    }
}