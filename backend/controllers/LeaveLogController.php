<?php
namespace backend\controllers;
use yii\filters\auth\CompositeAuth; 
use yii\filters\auth\QueryParamAuth;
use backend\models\LeaveLog;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\Response;

class LeaveLogController extends ActiveController
{
    public $modelClass = '\backend\models\LeaveLog';
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
    获取当前登录用户所有请假信息(根据参数？)
     */
    public function actionIndex()
    {
        return  0;
    }
    /*
    查看一条请假单信息&（包含流程）
     */
    public function actionView($id)
    {
        $model = LeaveLog::findOne(['id' => $id, 'status' => 0]);
        if (isset($model)) {
            $this->return['data'] = ArrayHelper::toArray($model, [
                'backend\models\LeaveLog' => [
                    'id',
                    'initiator_id',
                    'initiator'  => function ($model) {
                        return $model->initiator->username;
                    },
                    'leave_id',
                    'leave_type' => function ($model) {
                        return $model->leave->type;
                    },
                    'create_time'=>function($model){
                          return  date('h:i:s Y-m-d', $model->create_time);
                    },
                    'begin_time'=>function($model){
                          return  date('h:i:s Y-m-d', $model->begin_time);
                    },
                    'end_time'=>function($model){
                          return  date('h:i:s Y-m-d', $model->end_time);
                    },
                    'reason',
                    'process'    => function ($model) {
                        $tmp       = '';
                        $processes = $model->processes;
                        foreach ($processes as $key => $process) {
                            $sort = $process->sort;
                            $tmp[$sort]['user_id'] = $process->user->id;
                            $tmp[$sort]['username'] = $process->user->username;
                            $tmp[$sort]['status']  = $process->status;
                            $tmp[$sort]['desc']    = $process->desc;
                        }
                        return $tmp;
                    },
                ],
            ]);
        } else {
            $this->return['isSuccessful'] = false;
            $this->return['code']         = 4004;
            $this->return['message']      = '资源不存在';
        }
        return $this->return;
    }
    /*
    发起请假
     */
    public function actionCreate()
    {
        $model = new LeaveLog();
        $post  = Yii::$app->request->post();
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
    /*
    一个部门一段时间请假数量
     */
    public function actionGetDepartmeny(){
        
    }
    /*
    一个人一段时间请假数量
     */
  
    //获取某人全部请假记录
  public function actionAlllog($id)
  {
    $model = LeaveLog::findAll(['initiator_id' => $id, 'status' => [1,2,3]]);
    if ($model) {
        $this->return['data'] = ArrayHelper::toArray($model, [
                'backend\models\LeaveLog' => [
                    'id',
                    'initiator_id',
                    'status',
                    'initiator'  => function ($model) {
                        return $model->initiator->username;
                    },
                    'leave_id',
                    'leave_type' => function ($model) {
                        return $model->leave->type;
                    },
                    'create_time'=>function($model){
                          return  date('h:i:s Y-m-d', $model->create_time);
                    },
                    'begin_time'=>function($model){
                          return  date('h:i:s Y-m-d', $model->begin_time);
                    },
                    'end_time'=>function($model){
                          return  date('h:i:s Y-m-d', $model->end_time);
                    },
                    'reason',
                    'process'    => function ($model) {
                        $tmp       = '';
                        $processes = $model->processes;
                        foreach ($processes as $key => $process) {
                            $sort = $process->sort;
                            $tmp[$sort]['user_id'] = $process->user->id;
                            $tmp[$sort]['username'] = $process->user->username;
                            $tmp[$sort]['status']  = $process->status;
                            $tmp[$sort]['desc']    = $process->desc;
                        }
                        return $tmp;
                    },
                ],
            ]);
    } else {
        $this->return['isSuccessful'] = false;
        $this->return['code']         = 4004;
        $this->return['message']      = '资源不存在';
    }
    return $this->return;

  }

}
