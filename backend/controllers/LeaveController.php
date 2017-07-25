<?php
namespace backend\controllers;

use Yii;
use yii\filters\auth\CompositeAuth; 
use yii\filters\auth\QueryParamAuth;
use backend\models\Leave;
use yii\rest\ActiveController;
use yii\web\Response;

class LeaveController extends ActiveController
{
    public $modelClass = '\backend\models\Leave';
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
    public function actionUpdate($id)
    {
    $model = Leave::findOne(['id' => $id, 'status' => 1]);
        if ($model) {
            $model->update_time=time();
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
    创建请假类型
    */
    public function actionCreate()
    {
        $model = new Leave();
        $model->create_time=time();
        if($model->load(Yii::$app->request->post(), '') && $model->save()) {
            $this->return['data']=$model;
            $this->return['isSuccessful'] = true;
            return $this->return;
        }else{
            $this->return['isSuccessful'] = false;
            $this->return['code']         = 4004;
            $this->return['message']      = '资源不存在';
        }
            return $this->return;
    }
}
