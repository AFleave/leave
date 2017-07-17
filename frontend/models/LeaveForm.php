<?php
namespace frontend\models;

use yii\base\Model;
use frontend\models\Leavelog;

/**
 * Signup form
 */
class LeaveForm extends Model
{
    public $initiator_id;
    public $leave_id;
    public $detail;
    public $begin_time;
    public $end_time;
    public $procer_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['initiator_id', 'trim'],
            ['initiator_id', 'required'],
            ['initiator_id', 'number'],

            ['leave_id', 'trim'],
            ['leave_id', 'required'],
            ['leave_id', 'number'],

            ['detail', 'trim'],
            ['detail', 'required'],
            ['detail', 'string', 'min' => 1, 'max' => 120],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['begin_time', 'trim'],
            ['begin_time', 'required'],
            ['begin_time', 'number'],

            ['end_time', 'trim'],
            ['end_time', 'required'],
            ['end_time', 'number'],

            ['procer_id', 'trim'],
            ['procer_id', 'required'],
            ['procer_id', 'number'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'initiator_id' => '请假人',
            'leave_id' => '请假类型',
            'detail' => '请假原因',
            'begin_time' => '开始时间',
            'end_time' => '结束时间',
            'procer_id' => '审批人',
        ];
    }



    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function addleavelog()
    {  
        $log = new leavelog();
        $log->initiator_id = $this->initiator_id;
        $log->leave_id = $this->leave_id;
        $log->detail = $this->detail;
        $log->begin_time=$this->begin_time;
        $log->end_time=$this->end_time;
        $log->create_time=time();

        return $log->save() ? $log : null;
    }
}