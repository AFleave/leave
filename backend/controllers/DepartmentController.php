<?php
namespace backend\controllers;

use yii\filters\auth\CompositeAuth; 
use yii\filters\auth\QueryParamAuth;
use backend\models\Department;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\Response;

class DepartmentController extends ActiveController
{
    public $modelClass = '\backend\models\Department';
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
    所有组织基本信息
     */
    public function actionIndex()
    {
        $departments = Department::find()->where(['status' => 1])->orderBy('create_time')->all();
        if (isset($departments)) {
            $this->return['data'] = $departments;
            $this->return['isSuccessful'] = true;
        } else {
            $this->return['isSuccessful'] = false;
            $this->return['code']         = 4004;
            $this->return['message']      = '资源不存在';
        }
        return $this->return;
    }
    /*
    一个组织基本信息
     */
    public function actionView($id)
    {
        $department = Department::findOne(['id' => $id, 'status' => 1]);
        if (isset($department)) {
            $this->return['data'] = $department;
            $this->return['isSuccessful'] = true;
        } else {
            $this->return['isSuccessful'] = false;
            $this->return['code']         = 4004;
            $this->return['message']      = '资源不存在';
        }
        return $this->return;
    }
    /*
    获取一个组织基本信息和所有人员信息
     */
    public function actionGetPositions($dep_id)
    {
        $department = Department::findOne(['id' => $dep_id, 'status' => 1]);
        if (isset($department)) {
            $this->return['data'] = ArrayHelper::toArray($department, [
                'backend\models\Department' => [
                    'id',
                    'name',
                    'description',
                    'peopleNum' => function ($model) {
                        return count($model->positions);
                    },
                    'people'    => function ($model) {
                        $tmp       = '';
                        $positions = $model->positions;
                        foreach ($positions as $key => $position) {
                            $tmp[$key]['username']     = $position->user->username;
                            $tmp[$key]['pisitionName'] = $position->name;
                        }
                        return $tmp;
                    },
                ],
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
    获取所有部门基本信息和所有人员信息
     */
    public function actionGetAllPositions()
    {
        $departments = Department::find()->where(['status' => 1])->orderBy('create_time')->all();
        if (isset($departments)) {
            foreach ($departments as $department) {
                $this->return['data'][] = ArrayHelper::toArray($department, [
                    'backend\models\Department' => [
                        'id',
                        'name',
                        'description',
                        'peopleNum' => function ($model) {
                            return count($model->positions);
                        },
                        'people'    => function ($model) {
                            $tmp       = '';
                            $positions = $model->positions;
                            foreach ($positions as $key => $position) {
                                $tmp[$key]['position_id']    = $position->user->id;
                                 $tmp[$key]['position_name'] = $position->name;
                                $tmp[$key]['user_id']        = $position->user->id;
                                $tmp[$key]['username']       = $position->user->username;
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
    新增一个部门
     */
    public function actionCreate()
    {
        $model = new department();
        $post  = Yii::$app->request->post();
        $model->create_time=time();
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
    修改部门信息
     */
    public function actionUpdate($id)
    {
        $model = department::findOne(['id' => $id, 'status' => 1]);
        $post  = Yii::$app->request->post();
        if (isset($model)) {
            $model->updata_time=time();
            if ($model->load($post, '') && $model->save()) {
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
    获取该部门请假信息
     */
    public function actionGetLeaveLog($id)
    {

    }
}
