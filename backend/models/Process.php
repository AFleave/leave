<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "process".
 *
 * @property integer $id
 * @property integer $log_id
 * @property integer $user_id
 * @property integer $status
 * @property integer $sort
 * @property integer $created_time
 * @property integer $updata_time
 * @property string $desc
 *
 * @property LeaveLog $log
 * @property User $user
 */
class Process extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'process';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_id', 'user_id', 'sort', 'created_time'], 'required'],
            [['log_id', 'user_id', 'status', 'sort', 'created_time', 'updata_time'], 'integer'],
            [['desc'], 'string', 'max' => 255],
            [['log_id'], 'exist', 'skipOnError' => true, 'targetClass' => LeaveLog::className(), 'targetAttribute' => ['log_id' => 'id']],
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
            'log_id' => 'Log ID',
            'user_id' => 'User ID',
            'status' => 'Status',
            'sort' => 'Sort',
            'created_time' => 'Created Time',
            'updata_time' => 'Updata Time',
            'desc' => 'Desc',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLog()
    {
        return $this->hasOne(LeaveLog::className(), ['id' => 'log_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
