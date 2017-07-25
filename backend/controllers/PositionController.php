<?php
namespace backend\controllers;
use Yii;
use yii\filters\auth\CompositeAuth; 
use yii\filters\auth\QueryParamAuth;
use backend\models\Position;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\Response;

class PositionController extends ActiveController
{
    public $modelClass = '\backend\models\Position';
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
    public function actionIndex()
    {
        return 0;
    }
    /*
    根据user_id返回职务信息
    */
    public function actionView($id)
    {
        $position = Position::findOne(['user_id' => $id, 'status' => 1]);
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
            $this->return['isSuccessful'] = true;
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
                $this->return['isSuccessful'] = true;
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
    *新增职务信息
    */
    public function actionCreate()
    {
        $model = new position();
        $post  = Yii::$app->request->post();
        $model->created_time=time();
        if ($model->load($post, '') && $model->save()) {
            $this->return['data'] = $model;
            $this->return['isSuccessful'] = true;
        } else {
            $this->return['isSuccessful'] = false;
            $this->return['code']         = 4001;
            $this->return['message']      = '验证不通过';
        }
        return $this->return;
    }
}
