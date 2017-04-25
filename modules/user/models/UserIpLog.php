<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the model class for table "{{%user_ip_log}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $ip
 *
 * @property User $user
 */
class UserIpLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_ip_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'ip'], 'required'],
            [['user_id'], 'integer'],
            [['ip'], 'string', 'max' => 45],
            [['user_id', 'ip'], 'unique', 'targetAttribute' => ['user_id', 'ip'], 'message' => 'The combination of User ID and Ip has already been taken.'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'ip' => 'Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function setIp($ip)
    {
        $ips = explode(',', $ip);
        $this->ip = explode(',', $ips[0]);
    }
}