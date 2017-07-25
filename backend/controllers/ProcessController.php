<?php
namespace backend\controllers;

use Yii;
use yii\filters\auth\CompositeAuth; 
use yii\filters\auth\QueryParamAuth;
use backend\models\Process;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\Response;

class ProcessController extends ActiveController
{
    const STATUS_UNDO=0;//未处理
    const STATUS_GREE=1;//同意
    const STATUS_REFUSE=2;//驳回
    const STATUS_CONVEY=3;//专审
    public $modelClass = '\backend\models\Process';
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class'       => CompositeAuth::className(),
            'authMethods' => [
                QueryParamAuth::className(),
            ],
        ];
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }
    /*
    获取当前登录用户的审批(默认为未审批的)
     */
    public function actionView($id)
    {
        $processes = Process::find()->where(['status' => self::STATUS_UNDO, 'user_id' => $id])->orderBy('created_time')->all();
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
                                $tmp['sort'] = $process->sort;
                                $tmp[$process->user->id] = $process->user->username;
                                // $tmp['status'] = $process->status;    //返回貌似没意义
                            }
                            return $tmp;
                        },
                    ],
                ]);
            }
        $this->return['isSuccessful'] = true;
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
        $model = Process::findOne(['id' => $id, 'status' => self::STATUS_UNDO]);
        if (isset($model)) {
            $model->updata_time=time();
            if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
                $this->return['isSuccessful'] = true;
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
    *新增一个审批处理
    */
    public function actionCreate($sort=1)
    {
        $model = new Process();
        $post  = Yii::$app->request->post();
        $model->created_time=time();
        $model->sort=$sort;
        if ($model->load($post, '') && $model->save()) {
            $this->return['isSuccessful'] = true;
            $this->return['data'] = $model;
        } else {
            $this->return['isSuccessful'] = false;
            $this->return['code']         = 4001;
            $this->return['message']      = '验证不通过';
        }
        return $this->return;
    }
}
