<?php

namespace backend\models;
use Yii;
//  返回链接相关
use backend\models\User;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\Link;

use backend\models\Position;
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
 *
 * @property LeaveLog[] $leaveLogs
 * @property Position[] $positions
 * @property Process[] $processes
 */
class User extends ActiveRecord
{
    public static function tablename()
    {
        return 'user';
    }
    public function rules()
    {
        return [
            [['username', 'mobile', 'auth_key', 'password_hash', 'created_at', 'updated_at'], 'required'],
            [['mobile', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'email', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
}
