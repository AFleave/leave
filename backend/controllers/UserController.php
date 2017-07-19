<?php
namespace backend\controllers;

//输出格式
use backend\models\LoginForm;
//数据序列化
//认证
use backend\models\User; //1
use Yii; //2
use yii\filters\auth\CompositeAuth; //3
//速率限制 ，在认证类里实现接口
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\web\IdentityInterface;

class UserController extends ActiveController
{
    //指向数据模型
    public $modelClass = '\backend\models\User';
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        //认证
        $behaviors['authenticator'] = [
            'class'       => CompositeAuth::className(),
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
                QueryParamAuth::className(),
            ],
            //登录操作无需 验证
            'optional'    => [
                'login',
                'signup-test',
            ],
        ];
        // 内容协商 输出格式
        // $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }
/********************自定义方法开始*********************/
    public function actionSignupTest()
    {
        $user = new User();
        $user->generateAuthKey();
        $user->setPassword('123456');
        $user->username   = 'test';
        $user->mobile     = 15581646116;
        $user->email      = '1017990427@qq.com';
        $user->created_at = 1561313;
        $user->updated_at = 48461546;
        $user->save();
        return 0;
    }
    public function actionLogin()
    {
        $model = new LoginForm();
        $model->setAttributes(Yii::$app->request->post());
        if ($user = $model->login()) {
            if ($user instanceof IdentityInterface) {
                //instanceof 判断 变量是否属于该类的实例
                //用user接受返回的user,方便返回token，只需返回token！
                return $user->api_token;
            } else {
                return $user->errors;
            }
        } else {
            return $model->errors;
        }

    }
    public function actionIndex()
    {
        //应该返回什么？
        return 0;
    }
    /******返回传入id用户所有信息*********/
    public function actionView($id)
    {
        $user = User::find()->where(['id' => $id, 'status' => 1])->one();
        if (isset($user)) {
            $this->return['data'] = ArrayHelper::toArray($user, [
                '\backend\models\User' => [
                    'id',
                    'username',
                    'mobile',
                    'email',
                    'position' => function ($model) {
                        $tmp       = '';
                        $positions = $model->positions;
                        foreach ($positions as $key => $position) {
                            $tmp[$key]['departmentName'] = $position->department->name;
                            $tmp[$key]['positionName']   = $position->name;
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
    /***********修改用户资料**************/
    public function actionUpdate($id)
    {
        $model = User::findOne(['id' => $id, 'status' => 1]);
        if (isset($model)) {
            // 前端页面没用activeForm（没传model）,第二个参数要填（具体看源码）
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
    在这里获取该人所有请假单  ? 或是在leaveLog里写？
     */
    public function actionGetLeaveLog()
    {

    }
/********************自定义方法结束*********************/

/***************额外*************************/
    //对数据 分页 等操作
    // public $serializer = [
    //     'class' => 'yii\rest\Serializer',
    //     'collectionEnvelope' => 'items',
    // ];
    // 覆盖 ActiveController 的actions方法
    public function actions()
    {
        $actions = parent::actions(); // 父类有所有方法
        // 禁用"delete" 和 "create" 动作,必需禁用 下面才能覆盖
        unset($actions['delete'], $actions['create'], $actions['view'], $actions['index'], $actions['update']);
        // 使用"prepareDataProvider()"方法自定义数据provider
        // $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
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
        //     throw new \yii\web\ForbiddenHttpException(sprintf('You can only %s articles that you\'ve created.', $action));
        // }
    }
}
