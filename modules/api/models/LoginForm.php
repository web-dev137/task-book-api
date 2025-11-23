<?php

namespace app\modules\api\models;

class LoginForm extends \yii\base\Model
{
    public $login;
    public $password;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            ['login', 'string', 'max' => 255],
            ['password', 'string', 'min' => 6],
        ];
    }
}