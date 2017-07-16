<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "leave_log".
 *
 * @property integer $id
 * @property integer $initiator_id
 * @property integer $leave_id
 * @property varchar $detail
 * @property integer $create_time
 * @property integer $begin_time
 * @property integer $end_time
 * @property integer $status
 
 * @property User $initiator
 * @property Leave $leave
 * @property Process[] $processes
 */
class LeaveLog extends \yii\db\ActiveRecord
{
    const STATUS_SUCESS = 1;//审批同意
    const STATUS_FAIL = 2;//审批不通过
    const STATUS_UNDO = 0;//审批未完成
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'leave_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'initiator_id', 'leave_id', 'create_time', 'begin_time', 'end_time'], 'required'],
            [['id', 'initiator_id', 'leave_id', 'create_time', 'begin_time', 'end_time', 'status'], 'integer'],
            [['initiator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['initiator_id' => 'id']],
            [['leave_id'], 'exist', 'skipOnError' => true, 'targetClass' => Leave::className(), 'targetAttribute' => ['leave_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'initiator_id' => 'Initiator ID',
            'leave_id' => 'Leave ID',
            'create_time' => 'Create Time',
            'begin_time' => 'Begin Time',
            'end_time' => 'End Time',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInitiator()
    {
        return $this->hasOne(User::className(), ['id' => 'initiator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeave()
    {
        return $this->hasOne(Leave::className(), ['id' => 'leave_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcesses()
    {
        return $this->hasMany(Process::className(), ['log_id' => 'id']);
    }


    /*by user_id
    *返回
    *请假次数
    *请假成功
    *请假失败
    */
    public static function findLogtime($initiator_id)
    {
        $leave=array();
        $leave['success']=self::find()->where(['initiator_id'=>$initiator_id,'status'=>self::STATUS_SUCESS])->count();
        $leave['fail']=self::find()->where(['initiator_id'=>$initiator_id,'status'=>self::STATUS_FAIL])->count();
        $leave['undo']=self::find()->where(['initiator_id'=>$initiator_id,'status'=>self::STATUS_UNDO])->count();
        $leave['sum']=$leave['success']+$leave['fail']+$leave['undo'];
        return $leave;
    }

    /*
    *返回
    */
    public static function findbyid($id){
        $leaveinfo=self::find()->with('leave')->where(['id'=>$id])->one();
        $leave = ArrayHelper::toArray($leaveinfo, []);
        return $leave;
    }
}
