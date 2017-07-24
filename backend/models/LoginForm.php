<?php
namespace backend\models;

use backend\models\User;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;
/**
 * Login form
 */
class LoginForm extends Model
{
    public $mobile;
    public $password;

    private $_user;

    /****************LoginForm新增或修改 开始***************/
    const GET_API_TOKEN = 'generate_api_token';
    public function init()
    {
        parent::init();
        //事件绑定事件handle
        $this->on(self::GET_API_TOKEN, [$this, 'onGenerateApiToekn']);
    }
    public function login()
    {
        if ($this->validate()) {
            $this->trigger(self::GET_API_TOKEN);
            return $this->_user;
            //组件Yii::$app->user->login() 为通过session或cookie方式登录
            // return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
    /*
    登录验证成功，生成token
     */
    public function onGenerateApiToekn()
    {
        //登录用户的token不存在或过期则生成
        if (!User::apiTokenIsValid($this->_user->api_token)) {
            $this->_user->generateApiToken();
        }

        //保存到数据库
        $this->_user->save(false);
    }
    public function afterValidate()
    {
        if ($this->hasErrors()) {
            $errors = $this->errors;
            $errors = current($errors);
            throw new NotFoundHttpException($errors[0], 1);
        }
        return true;
    }
    /****************LoginForm新增或修改 结束***************/
    public function rules()
    {
        return [
            [['mobile', 'password'], 'required', 'message' => '手机号或密码不得为空'],
            ['password', 'validatePassword'],
        ];
    }
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '手机号或密码不对.');
            }
        }
    }
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByMobile($this->mobile);
        }

        return $this->_user;
    }
}
