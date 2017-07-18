<?php
namespace backend\controllers;

use backend\models\Leave;
use yii\rest\ActiveController;
use yii\web\Response;

class LeaveController extends ActiveController
{
    public $modelClass = '\backend\models\Leave';
    public function behaviors()
    {
        $behaviors                                              = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }
    /*
    返回所有请假类型
     */
    public function actionIndex()
    {
        $model = Leave::findAll(['status' => 1]);
        if (isset($model)) {
            $this->return['data'] = $model;
        } else {
            $this->return['isSuccessful'] = false;
            $this->return['code']         = 4004;
            $this->return['message']      = '资源不存在';
        }
        return $this->return;
    }
    /*

     */
    public function actionView($id)
    {
        $model = Leave::findOne(['id' => $id, 'status' => 1]);
        if (isset($model)) {
            $this->return['data'] = $model;
        } else {
            $this->return['isSuccessful'] = false;
            $this->return['code']         = 4004;
            $this->return['message']      = '资源不存在';
        }
        return $this->return;
    }
    /*
    修改请假类型
     */
    public function actionUpdate()
    {

    }
    /*
    创建请假类型
    */
    public function actionCreate(){
        
    }
}
