<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "leave".
 *
 * @property integer $id
 * @property string $type
 * @property string $desc
 * @property integer $status
 * @property integer $create_time
 * @property integer $updata_time
 *
 * @property LeaveLog[] $leaveLogs
 */
class Leave extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'leave';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'create_time'], 'required'],
            [['status', 'create_time', 'updata_time'], 'integer'],
            [['type'], 'string', 'max' => 20],
            [['desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'desc' => 'Desc',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'updata_time' => 'Updata Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeaveLogs()
    {
        return $this->hasMany(LeaveLog::className(), ['leave_id' => 'id']);
    }

    /*
    *返回请假类型及id  请假页面
    */
    public static function findleave(){
     return   $leave=(new \yii\db\Query())
                ->select(['id','type'])
                ->from('leave')
                ->all();
    }

}
