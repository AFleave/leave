<?php
namespace backend\controllers;
use Yii;
use backend\models\Position;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\Response;

class PositionController extends ActiveController
{
    public $modelClass = '\backend\models\Position';
    public function behaviors()
    {
        $behaviors                                              = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }
    public function actionIndex()
    {
        return 0;
    }
    /*
    貌似没必要的方法
    */
    public function actionView($id)
    {
        $position = Position::findOne(['id' => $id, 'status' => 1]);
        if (isset($position)) {
            $this->return['data'] = ArrayHelper::toArray($position, [
                'backend\models\Position' => [
                	'id',
                	'name',
                	'username' => function($model){
                		return $model->user->username;
                	},
                	'department' => function ($model){
                		return $model->department->name;
                	},
                ]
            ]);
        } else {
            $this->return['isSuccessful'] = false;
            $this->return['code']         = 4004;
            $this->return['message']      = '资源不存在';
        }
        return $this->return;
    }
    /*
        修改职位信息，如该职位部门变更
    */
    public function actionUpdate($id){
        $model = Position::findOne(['id' => $id,'status' => 1]);
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
