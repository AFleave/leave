<?php
namespace backend\models;

use yii\base\Model;
use backend\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $mobile;
    public $password;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message' => '名字不能为空' ],
            ['username', 'string','max' => 255,],

            ['mobile', 'trim'],
            ['mobile', 'required','message' => '手机号不能为空'],
            ['mobile', 'number'],
            ['mobile','validatePhone'],
            ['mobile', 'unique', 'targetClass' => '\backend\models\User', 'message' => '手机号已注册'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function validatePhone($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $len=strlen($this->mobile);
            $pattern="/0?(13|14|15|18)[0-9]{9}/";
            $result=preg_match($pattern,$this->mobile);
            if ($len!=11||!$result) {
                $this->addError($attribute, '手机格式错误.');
            }
            return true;
        }
    }


    public function afterValidate ()
    {
        if ($this->hasErrors()) {
            $errors = $this->errors;
            $errors = current($errors);
            throw new \yii\web\NotFoundHttpException($errors[0], 1);
        }
        return true;
    }


    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();
        $user->username = $this->username;
        $user->mobile = $this->mobile;
        $user->created_at = time();
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateApiToken();
        return $user->save()?$user->api_token:null;
    }
}
