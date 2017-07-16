<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
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
 *
 * @property LeaveLog $log
 * @property User $user
 */
class Process extends \yii\db\ActiveRecord
{

    const STATUS_SUCESS = 1;//审批同意
    const STATUS_FAIL = 2;//审批不同意
    const STATUS_UNDO = 0;//审批未处理
    const STATUS_CONV = 3;//审批已转交
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
            [['id', 'log_id', 'user_id', 'sort', 'created_time'], 'required'],
            [['id', 'log_id', 'user_id', 'status', 'sort', 'created_time', 'updata_time'], 'integer'],
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


    /*
    *返回我未审批别人的
    */
    public static function findWaitme($user_id){
        $waitme=array();
        $rows=self::find()->where(['user_id'=>$user_id,'status'=>self::STATUS_UNDO])->all();
        $i=0;
        foreach ($rows as $row) {
            $waitme[$i]['id'] = $row->id;
            $waitme[$i]['log_id'] = $row->log_id;
            $waitme[$i]['sort'] = $row->sort;
            $i++;
        }
        return $waitme;
    }

    /*
    *返回我已审批审批别人的  限制在五条
    */
    public static function findHavedbyme($user_id){
        $haveme=array();
        $rows=(new \yii\db\Query())
                ->from('Process')
                ->where(['and','user_id'=>$user_id,'status >0'])
                ->orderBy('updata_time desc') //按照updata_time 倒序
                ->batch(5); //每次取五条为数组的第一个 假限定取五条 找到办法再改
        $haveme= ArrayHelper::toArray($rows, []);
        return $haveme[0];
    }


    /*
    *修改指定记录的$status  以及插入该记录的 $desc
    */

    public static function updateStatudesc($process_id,$status,$desc=null){
        if (isset($process_id)) {
                    $model = Process::find()->where(['id' => $process_id])->one();
                    $model->status = $status;
                    $model->desc = $desc;
                    $model->updata_time = time();
                    $model->save();
                    if($model->save()){
                        return 1;
                    }else{
                        return 0;
                    }
        }
    }

}

