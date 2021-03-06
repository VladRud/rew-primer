<?php

namespace app\modules\core\models;

use app\modules\core\models\queries\EmailTemplateQuery;
use Yii;

/**
 * This is the model class for table "{{%email_template}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $content
 * @property string $subject
 *
 * @property EmailQueue[] $emailQueues
 */
class EmailTemplate extends \yii\db\ActiveRecord
{
    const TEMPLATE_CONTACT_US = 1000;

    const TEMPLATE_INVITATION_REQUEST_RECEIVED = 1;
    const TEMPLATE_INVITATION_REQUEST_APPROVED = 2;

    const TEMPLATE_REGISTER_CONFIRMATION = 3;
    const TEMPLATE_REGISTER_SUCCESS = 4;

    const TEMPLATE_USER_PASSWORD_RECOVERY = 5;
    const TEMPLATE_ADMIN_PASSWORD_RECOVERY = 6;

    const TEMPLATE_REGISTER_REFERRAL_BONUS = 7;

    const TEMPLATE_USER_BLOCKED = 8;
    const TEMPLATE_USER_UNBLOCKED = 9;

    const TEMPLATE_ORDER_NEW = 10;
    const TEMPLATE_ORDER_DECLINED = 11;
    const TEMPLATE_ORDER_APPROVED = 12;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%email_template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'subject'], 'required'],
            [['content'], 'string'],
            [['name', 'subject'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Template Name',
            'content' => 'Content',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmailQueues()
    {
        return $this->hasMany(EmailQueue::className(), ['template_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return EmailTemplateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmailTemplateQuery(get_called_class());
    }
}
