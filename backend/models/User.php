<?php

namespace backend\models;

use backend\models\Position;
//  返回链接相关
use backend\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use yii\web\Link;
use \yii\web\UnauthorizedHttpException;
/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property integer $mobile
 * @property string $email
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $api_token
 *
 * @property LeaveLog[] $leaveLogs
 * @property Position[] $positions
 * @property Process[] $processes
 */
class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    // public static $api_token;
    /****************认证类**********************************/
    /*接口五方法开始*/
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }
    /*
    api的token认证
    */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if(!static::apiTokenIsValid($token)){
            throw new UnauthorizedHttpException('token无效');
        }
        return static::findOne(['api_token' => $token]);
    }
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    /*接口五方法结束*/
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    /*
    生成api-token
     */
    public function generateApiToken()
    //这里方法不能静态化因为方法里要设置 对象 的属性
    {
        $this->api_token = Yii::$app->security->generateRandomString() . '_' . time();
        //随机字符串 加 当前时间戳
    }
    /*
    验证api-token是否有效
     */
    public static function apiTokenIsValid($token)
    //能静态化就静态化！不涉及对象的
    {
        if (empty($token)) {
            return false;
        }
        //substr(string, start)     - 返回指定字符(string) 的子串（由），这里返回start数字剩下的字符串(1代表字符串第一个)
        //strrpos(haystack, needle) - 计算指定字符串(needle)在目标字符串(haystack)中最后一次出现的位置
        /*判断是否过时*/
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire    = Yii::$app->params['user.apiTokenExpire']; //定义全局用的值可考虑通过params统一管理
        return $timestamp + $expire >= time();
    }
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
    
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /****************认证类***********************************/
    public static function tablename()
    {
        return 'user';
    }
    public function rules()
    {
        return [
            [['username', 'mobile', 'auth_key', 'password_hash', 'created_at'], 'required'],
            [['mobile', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'email', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            ['api_token','string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                   => 'ID',
            'username'             => 'Username',
            'mobile'               => 'Mobile',
            'email'                => 'Email',
            'auth_key'             => 'Auth Key',
            'password_hash'        => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'status'               => 'Status',
            'created_at'           => 'Created At',
            'updated_at'           => 'Updated At',
        ];
    }
    //实现同时你想屏蔽掉一些敏感字段
    public function fields()
    {
        $fields = parent::fields(); //默认返回所有字段
        // 删除一些包含敏感信息的字段
        unset($fields['password_hash'],
            $fields['auth_key'],
            $fields['password_reset_token'],
            $fields['created_at'],
            $fields['status']
        );
        return $fields;
    }
    // 返回对象
    public function extraFields()
    {
        return ['email'];
    }
    //实现接口Linkables getLinks方法 : 返回链接
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['books', 'id' => $this->id], true),
        ];
    }
    //集合
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeaveLogs()
    {
        return $this->hasMany(LeaveLog::className(), ['initiator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPositions()
    {
        return $this->hasMany(Position::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcesses()
    {
        return $this->hasMany(Process::className(), ['user_id' => 'id']);
    }

/***********************额外************************/
    // 覆盖 fields方法 控制返回的字段 ，默认返回全部
    // public function fields()
    // {
    //     return [
    //         // 输出名 => 数据库字段名
    //         //   'id' => 'id',
    //         // 'info' => function ($model){
    //         //     return $arr = [
    //         //       'id' => $model->id,
    //         //     ];
    //         // },
    //         // 'position' => function ($model){
    //         //     return $this->getPosition($model->id);
    //         // }
    //     ];
    // }

    public static function findByMobile($mobile)
    {
        return static::findOne(['mobile' => $mobile, 'status' => self::STATUS_ACTIVE]);
    }


}
