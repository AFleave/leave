<?php
namespace frontend\models;

use yii\base\Model;
use frontend\models\User;

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
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['mobile', 'trim'],
            ['mobile', 'required'],
            ['mobile', 'number'],
            ['mobile','validatePhone'],
            ['mobile', 'unique', 'targetClass' => '\frontend\models\User', 'message' => 'This mobile has already been taken.'],

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
        }
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
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
