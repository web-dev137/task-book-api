<?php

namespace app\modules\api\controllers;

use app\modules\api\models\LoginForm;
use app\models\User;
use Yii;

class UsersController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // CORS should run before authentication so preflight OPTIONS requests are handled.
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];
        $behaviors['authenticator'] = [
                'class' => \yii\filters\auth\HttpBearerAuth::class,
        ];
        // Limit authenticator to write actions; skip OPTIONS so preflight isn't authenticated.
        $behaviors['authenticator']['only'] = [
                'update',
                'delete',
                'view'
            ];
           
        $behaviors['authenticator']['except'] = ['options'];

        // Ensure allowed HTTP verbs and allow OPTIONS for preflight
        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::class,
            'actions' => [
                'view' => ['GET', 'HEAD'],
                'sign-up' => ['POST'],
                'login' => ['POST'],
                'update' => ['PUT', 'PATCH'],
                'delete' => ['DELETE'],
                'options' => ['OPTIONS'],
            ],
        ];

        return $behaviors;
    }

    public function actionLogin()
    {
        $login = \Yii::$app->request->post('login');
        $password = \Yii::$app->request->post('password');
        $user = User::findOne(['login' => $login]);

        if ($user && $user->validatePassword($password)) {
            // Generate JWT token
           $token = \Firebase\JWT\JWT::encode(
            payload: [
                'iis' => Yii::$app->request->hostName,
                'aud' => Yii::$app->request->hostName,
                'iat' => time(),
                'nbf' => time(),
                'exp' => time() + 3600*8,
                'data' => [
                    'userId' => $user->id,
                    'login' => $user->login
                ]
            ],
            key: Yii::$app->params['jwtSecretKey'],
            alg: 'HS256');

            return ['token' => (string)$token];
        } else {
            \Yii::$app->response->statusCode = 401;
            return ['error' => 'Invalid login or password'];
        }
    }

    public function actionSignUp()
    {
        $login = \Yii::$app->request->post('login');

        $userForm = new LoginForm();

        $userExist = User::findOne(['login' => $login]);

        if($userExist) {
            \Yii::$app->response->statusCode = 400;
            return ['error' => 'User already exists'];
        }

        if ($userForm->load(Yii::$app->request->post(), '') && $userForm->validate()) {
            $user = new User();
            $user->login = $userForm->login;
            $user->setPassword($userForm->password);
            $user->generateAuthKey();
            if ($user->save()) {
                $authManager = Yii::$app->authManager;
                $permission = $authManager->getPermission('ownBook');
                $authManager->assign($permission,$user->id);
                return ['user' => $user];
            } else {
                \Yii::$app->response->statusCode = 500;
                return ['error' => $user->getErrors()];
            }
        } else {
            \Yii::$app->response->statusCode = 400;
            return ['error' => $userForm->getErrors()];
        }
    }


}