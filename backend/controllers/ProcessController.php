<?php
namespace backend\controllers;

use backend\models\Process;
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
    获取当前登录用户审批
     */
    public function actionIndex($status = 0)
    {
        $id    = 5;
        $model = Process::find()->where(['status' => $status , 'user_id' => $id])->orderBy('created_time')->all();
        foreach(){
            
        }
        return $model;
    }
    /*
    审核
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
}
