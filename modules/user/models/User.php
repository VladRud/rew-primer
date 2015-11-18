<?php

namespace app\modules\user\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\user\helpers\Password;
use app\modules\user\models\UserMeta;
use app\modules\user\models\UserGroupRelations;
use app\helpers\MandrillEmailHelper;

/**
 * Description of User
 *
 * @author Stableflow
 * 
 * Database fields:
 * @property integer $id
 * @property string  $username
 * @property string  $email
 * @property integer $role
 * @property string  $password
 * @property string  $create_date
 * @property string  $referral_code
 * @property integer $status
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface {

    const ROLE_ADMIN        = 1;
    const ROLE_USER         = 2;
    const ROLE_PARTNER      = 3;
    const ROLE_MOBILE_USER  = 4;
    
    const STATUS_PENDING    = 0;
    const STATUS_APPROVED   = 1;
    const STATUS_BLOCKED    = 2;
    const STATUS_TRANSFER   = 3;
    const STATUS_BLACKLIST  = 4;
    
    const CREATE_SCENARIO   = 'create';
    const UPDATE_SCENARIO   = 'update';
    const REGISTER_SCENARIO = 'register';
    const LOGIN_SCENARIO    = 'login';
    
    const API_REGISTER_SCENARIO = 'api_register';

    public $newPassword;
    public $confirmPassword;
    
    protected $_oldStatus;


    protected $metaData;

    /** @inheritdoc */
    public static function tableName() {
        return '{{%users}}';
    }

    /** @inheritdoc */
    public function attributeLabels() {
        return [
            'username'          => Yii::t('app', 'Username'),
            'email'             => Yii::t('app', 'Email'),
            'role'              => Yii::t('app', 'Role'),
            'password'          => Yii::t('app', 'Password'),
            'create_date'       => Yii::t('app', 'Registration time'),
            'referral_code'     => Yii::t('app', 'Referral Code'),
            'newPassword'       => Yii::t('app', 'Password'),
            'confirmPassword'   => Yii::t('app', 'Confirm Password'),
            'status'            => Yii::t('app', 'Status'),
            
            'last_name'         => Yii::t('app', 'Last Name'),
            'first_name'        => Yii::t('app', 'First Name'),
            'city'              => Yii::t('app', 'City'),
            'state'             => Yii::t('app', 'State'),
            'companyName'       => Yii::t('app', 'Company Name'),
            'address'           => Yii::t('app', 'Address'),
            'zip'               => Yii::t('app', 'Zip'),
            'phone'             => Yii::t('app', 'Phone'),
            'note'              => Yii::t('app', 'Note'),
        ];
    }

    /** @inheritdoc */
    public function scenarios() {
        return\yii\helpers\ArrayHelper::merge([
                static::LOGIN_SCENARIO     => ['username', 'email'],
                static::REGISTER_SCENARIO  => ['username', 'email', 'password'],
                static::API_REGISTER_SCENARIO  => ['username', 'email', 'password', 'device_id', 'device_os'],
                static::CREATE_SCENARIO    => [
                    'username', 
                    'email', 
                    'newPassword', 
                    'confirmPassword', 
                    'status', 
                    'role',
                    'last_name',
                    'first_name',
                    'city',
                    'state',
                    'companyName',
                    'address',
                    'zip',
                    'phone',
                    'note',
                ],
                static::UPDATE_SCENARIO    => [
                    'username', 
                    'email', 
                    'newPassword', 
                    'confirmPassword', 
                    'status', 
                    'role',
                    'last_name',
                    'first_name',
                    'city',
                    'state',
                    'companyName',
                    'address',
                    'zip',
                    'phone',
                    'note',
                ],
            ], parent::scenarios());
    }

    /** @inheritdoc */
    public function rules() {
        return [
            // username rules
            ['username', 'required'],
            ['username', 'unique'],
            ['username', 'match', 'pattern' => '/^[-a-zA-Z0-9_\.@]+$/'],
            ['username', 'string', 'min' => 3, 'max' => 60],
            ['username', 'trim'],
            // email rules
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 100],
            ['email', 'unique'],
            ['email', 'trim'],
            // password rules
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            // new password rules
            ['newPassword', 'required', 'on' => 'create'],
            ['newPassword', 'string', 'min' => 6],
            // confirm password rules
            ['confirmPassword', 'required', 'on' => 'create'],
            ['confirmPassword', 'string', 'min' => 6],
            ['confirmPassword', 'compare', 'compareAttribute' => 'newPassword'],
            // status rules
            [['status'], 'integer'],
            // role rules
            [['role'], 'integer'],
            // referral code
            [['referral_code'], 'string', 'max' => 12],
            
            [['device_id'], 'required', 'on' => static::API_REGISTER_SCENARIO],
            [['device_os'], 'required', 'on' => static::API_REGISTER_SCENARIO],
            
            [
                [
                    'last_name',
                    'first_name',
                    'city',
                    'state',
                    'companyName',
                    'address',
                    'zip',
                    'phone',
                    'note',
                    
                ], 'safe'
            ]
        ];
    }
    
    /** @inheritdoc */
    public function beforeValidate() {
        if (!empty($this->newPassword)) {
            $this->password = $this->newPassword;
        }
        return parent::beforeValidate();
    }

    /** @inheritdoc */
    public function beforeSave($insert) {
        if ($insert) {
            if ($this->scenario == static::CREATE_SCENARIO) {
                $this->password = Password::hash($this->newPassword);
            } else {
                $this->password = Password::hash($this->password);
            }
            
            $this->create_date = \app\helpers\DateHelper::getCurrentDateTime();
        } else {
            if (!empty($this->newPassword)) {
                $this->password = Password::hash($this->newPassword);
            }
            
            if($this->status === static::STATUS_BLOCKED){
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
        }

        return parent::beforeSave($insert);
    }

    /** @inheritdoc */
    public function getId() {
        return $this->getAttribute('id');
    }

    /** @inheritdoc */
    public function getAuthKey() {
        return $this->getAttribute('auth_key');
    }

    /** @inheritdoc */
    public function validateAuthKey($authKey) {
        return $this->getAttribute('auth_key') == $authKey;
    }

    /** @inheritdoc */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    /** @inheritdoc */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @return boolean Whether the user is blocked or not.
     */
    public function getIsBlocked() {
        return $this->status == self::STATUS_BLOCKED;
    }

    /**
     * @return boolean Whether the user is approved or not.
     */
    public function getIsApproved() {
        return $this->status == self::STATUS_APPROVED;
    }
    
    /**
     * @return boolean Whether the user is approved or not.
     */
    public function getIsBlacklist() {
        return $this->status == self::STATUS_BLACKLIST;
    }

    /**
     * Finds a user by the given username or email.
     *
     * @param  string      $usernameOrEmail Username or email to be used on search.
     * @return models\User
     */
    public function findUserByUsernameOrEmail($usernameOrEmail) {
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->findUserByEmail($usernameOrEmail);
        }
        return $this->findUserByUsername($usernameOrEmail);
    }

    /**
     * Finds a user by the given email.
     *
     * @param  string      $email Email to be used on search.
     * @return models\User
     */
    public function findUserByEmail($email) {
        return self::findOne(['email' => $email]);
    }

    /**
     * Finds a user by the given username.
     *
     * @param  string      $username Username to be used on search.
     * @return models\User
     */
    public function findUserByUsername($username) {
        return self::findOne(['username' => $username]);
    }

    /**
     * Get status list
     * @return array
     */
    public static function getStatusList() {
        return [
            static::STATUS_APPROVED     => Yii::t('app', 'Approved'),
            static::STATUS_BLOCKED      => Yii::t('app', 'Blocked'),
            static::STATUS_PENDING      => Yii::t('app', 'Pending'),
            static::STATUS_TRANSFER     => Yii::t('app', 'Transfer'),
            static::STATUS_BLACKLIST    => Yii::t('app', 'Blacklist'),
        ];
    }

    /**
     * Get status
     * @param boolean $html 
     * @return string 
     */
    public function getStatus($html = false) {
        $data = $this->getStatusList();
        if (isset($data[$this->status])) {
            if (false !== $html) {
                switch ($this->status) {
                    case static::STATUS_APPROVED :
                        $status = 'success';
                        break;
                    case static::STATUS_BLOCKED:
                        $status = 'danger';
                        break;
                    case static::STATUS_PENDING:
                        $status = 'info';
                        break;
                    case static::STATUS_TRANSFER:
                        $status = 'primary';
                        break;
                    case static::STATUS_BLACKLIST:
                        $status = 'default';
                        break;
                }
                return "<span class=\"label label-sm label-$status\">{$data[$this->status]}</span>";
            }
            return $data[$this->status];
        }

        return 'unknown';
    }

    /**
     * Get role list
     * @return array
     */
    public static function getRoleList() {
        return [
            static::ROLE_USER   => Yii::t('app', 'User'),
            static::ROLE_ADMIN  => Yii::t('app', 'Admin'),
        ];
    }

    /**
     * Get role
     * @return string Description
     */
    public function getRoles() {
        $roles = static::getRoleList();

        if (isset($roles[$this->role]))
            return $roles[$this->role];

        return 'unknown';
    }

    /**
     * Resets password.
     * 
     * @param string $password
     * @return boolean
     */
    public function resetPassword($password) {
        return (bool) $this->updateAttributes(['password' => Password::hash($password)]);
    }

    /**
     * @return bool Whether the user is confirmed or not.
     */
    public function getIsConfirmed() {
        return $this->status !== static::STATUS_PENDING;
    }

    /**
     * Get user by referral code
     * @param string $code 
     * @return models/User 
     */
    public static function getUserByReferralCode($code) {
        return self::findOne(['referral_code' => $code]);
    }

    /**
     * Register new user
     * @param User $referral
     * @return boolean
     */
    public function register($referral = null) {
        $this->role = static::ROLE_USER;
        $this->status = static::STATUS_APPROVED;
        if ($this->save()) {
            if (null !== $referral) {
                Referral::linkReferral($referral->id, $this->id);
            }
            Yii::$app->user->login($this);
            return true;
        }
        return false;
    }

    /**
     */
    public function getReturnUrl() {
        switch ($this->role) {
            case static::ROLE_ADMIN:
                $url = '/dashboard/dashboard-backend/index';
                break;
            case static::ROLE_USER:
            case static::ROLE_MOBILE_USER:
            default :
                $url = Yii::$app->getModule('user')->loginSuccess;
                break;
        }
        return $url;
    }

    /**
     */
    public function active() {
        return $this->andWhere('status != :status', [':status' => static::STATUS_APPROVED]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeta(){
        return $this->hasMany(UserMeta::className(), ['user_id' => 'id']);
    }
    
    /** @inheritdoc */
    public function afterFind() {
        $this->metaData = new \stdClass();
        foreach ($this->meta as $key => $value){
            $this->metaData->{$value->meta_key} = $value->meta_value;
        }
    }
    
    /**
     * Get user meta data
     * @return object
     */
    public function getMetaData() {
        return $this->metaData;
    }
    
    public function getAvatar() {
        return isset($this->metaData->avatar) ? $this->metaData->avatar : null;
    }
    
    public function getLastName() {
        return isset($this->metaData->last_name) ? $this->metaData->last_name : null;
    }
    
    public function getFirstName() {
        return isset($this->metaData->first_name) ? $this->metaData->first_name : null;
    }
    
    public function getAbout() {
        return isset($this->metaData->about) ? $this->metaData->about : null;
    }
    
    public function getName() {
        return isset($this->metaData->last_name, $this->metaData->first_name) ? "{$this->metaData->last_name}  {$this->metaData->first_name}" : null;
    }
    
    public function getPhone() {
        return isset($this->metaData->phone) ? $this->metaData->phone : null;
    }
    
    public function getInterests() {
        return isset($this->metaData->interests) ? $this->metaData->interests : null;
    }
    
}
