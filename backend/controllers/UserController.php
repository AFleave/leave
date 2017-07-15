<?php

namespace backend\controllers;

use yii\rest\ActiveController;
//输出格式
use yii\web\Response;
//数据序列化
use yii\rest\Serializer;
//认证
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;		//1
use yii\filters\auth\HttpBearerAuth;	//2
use yii\filters\auth\QueryParamAuth;	//3
//速率限制 ，在认证类里实现接口
use yii\filters\RateLimiter;

use backend\models\User;
use Yii;

class UserController extends ActiveController{
	//指向数据模型
	public $modelClass = 'backend\models\User';
	//对数据 分页 等操作
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
	public function behaviors(){
		$behaviors = parent::behaviors();
        //认证
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                /*下面是三种验证access_token方式*/
                //1.HTTP 基本认证: access token 当作用户名发送，应用在access token可安全存在API使用端的场景，例如，API使用端是运行在一台服务器上的程序。
                // HttpBasicAuth::className(),
                //2.OAuth 2: 使用者从认证服务器上获取基于OAuth2协议的access token，然后通过 HTTP Bearer Tokens 发送到API 服务器。
                //HttpBearerAuth::className(),
                //3.请求参数: access token 当作API URL请求参数发送，这种方式应主要用于JSONP请求，因为它不能使用HTTP头来发送access token
                //http://localhost/user/index/index?access-token=123
                //http://localhost/yii/frontend/web/books?access-token=123
                // http://localhost/yii/frontend/web/books/1?access-token=123
                // QueryParamAuth::className(),
            ],
            //登录等操作无需 验证
            'optional' => [
                    'login',
                    'signup-test'
            ],
        ];
		// 内容协商 输出格式 
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
	}
    // 覆盖 ActiveController 的actions方法
	public function actions()
	{
	    $actions = parent::actions();	// 父类有所有方法
	    // 禁用"delete" 和 "create" 动作,必需禁用 下面才能覆盖
	    // unset($actions['delete'], $actions['create']);
	    // 使用"prepareDataProvider()"方法自定义数据provider 
	    // $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
	    return $actions;
	}
	public function actionTest(){
		return 0;
	}
	public function actionAdd(){
		$request = Yii::$app->request;
		$b = $request->post();
		return $b;
	}
	public function actionSearch($id){
		$user = User::findOne(array('id' => $id));
		return $user;
	}
	public function prepareDataProvider()
	{
		// echo '调用了prepareDataProvider()';
	    // 为"index"动作准备和返回数据provider
	}
	// 覆盖 ActiveController 的checkAccess 方法 授权
	// 默认action在父类调用了checkaccess方法，因此自定义 action 应 调用 checkaccess方法 
	public function checkAccess($action, $model = null, $params = [])
	{
	    // 检查用户能否访问 $action 和 $model
	    // 访问被拒绝应抛出ForbiddenHttpException 
	    // if ($action === 'update' || $action === 'delete') {
	    //     if ($model->author_id !== \Yii::$app->user->id)
	    //         throw new \yii\web\ForbiddenHttpException(sprintf('You can only %s articles that you\'ve created.', $action));
	    // }
	    //试一下
	    // if($action === 'index'){
	    // 	throw new \yii\web\ForbiddenHttpException(sprintf('You can only %s articles that you\'ve created.', $action));
	    // }
	}			
}