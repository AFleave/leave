<?php
namespace backend\controllers;

use backend\models\Process;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\Response;

class ProcessController extends ActiveController
{
    public $modelClass = '\backend\models\Process';
    public function behaviors()
    {
        $behaviors                                              = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }
    /*
    获取当前登录用户的审批(默认为未审批的)
     */
    public function actionIndex($status = 0)
    {
        $id        = 5;//伪用户
        $processes = Process::find()->where(['status' => $status, 'user_id' => $id])->orderBy('created_time')->all();
        if (isset($processes)) {
            foreach ($processes as $process) {
                $this->return['data'] = ArrayHelper::toArray($process, [
                    'backend\models\Process' => [
                        'id',
                        'sort',
                        'created_time',
                        'leave' => function ($model){
                            $tmp = '';
                            $log = $model->log;
                            $tmp[$log->initiator_id] = $log->initiator->username;
                            $tmp[$log->leave_id] = $log->leave->type;
                            $tmp['begin_time'] = $log->begin_time;
                            $tmp['end_time'] = $log->end_time;
                            $tmp['reason'] = $log->reason;
                            return $tmp;
                        },
                        'process' => function ($model) {
                            $tmp = '';
                            $processes = $model->log->processes; //如何排序
                            array_pop($processes);  //去掉最后一个（即本人）
                            foreach($processes as $process){
                                $tmp['process_id'] = $process->id;
                                $tmp[$process->user->id] = $process->user->username;
                                // $tmp['status'] = $process->status;    //返回貌似没意义
                            }
                            return $tmp;
                        },
                    ],
                ]);
            }
        } else {
            $this->return['isSuccessful'] = false;
            $this->return['code']         = 4004;
            $this->return['message']      = '资源不存在';
        }
        return $this->return;
    }
    /*
    提交审核结果
     */
    public function actionUpdate($id)
    {
        $model = Process::findOne(['id' => $id, 'status' => 1]);
        if (isset($model)) {
            if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
                $this->return['data'] = $model;
            } else {
                $this->return['isSuccessful'] = false;
                $this->return['code']         = 4001;
                $this->return['message']      = '验证不通过';
            }
        } else {
            $this->return['isSuccessful'] = false;
            $this->return['code']         = 4004;
            $this->return['message']      = '资源不存在';
        }
        return $this->return;
    }
    /*

 */
}
