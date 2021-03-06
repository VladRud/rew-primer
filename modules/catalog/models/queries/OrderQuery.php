<?php

namespace app\modules\catalog\models\queries;
use app\modules\catalog\models\Order;

/**
 * This is the ActiveQuery class for [[\app\modules\catalog\models\Order]].
 *
 * @see \app\modules\catalog\models\Order
 */
class OrderQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\modules\catalog\models\Order[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\catalog\models\Order|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $status
     * @return $this
     */
    public function status($status)
    {
        return $this->andWhere([Order::tableName() . '.status' => $status]);
    }
}
