<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "leave_log".
 *
 * @property integer $id
 * @property integer $initiator_id
 * @property integer $leave_id
 * @property integer $create_time
 * @property integer $begin_time
 * @property integer $end_time
 * @property integer $status
 *
 * @property User $initiator
 * @property Leave $leave
 * @property Process[] $processes
 */
class LeaveLog extends \yii\db\ActiveRecord
{
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
            [['initiator_id', 'leave_id', 'create_time', 'begin_time', 'end_time'], 'required'],
            [['initiator_id', 'leave_id', 'create_time', 'begin_time', 'end_time', 'status'], 'integer'],
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
    // public function fields()
    // {
    //     return [
    //         'id',
    //         ''
    //     ];
    // }
}
