<?php

namespace app\modules\api\controllers;

use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class BooksController extends ActiveController
{
    public $modelClass = 'app\modules\api\models\Book';
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // CORS should run before authentication so preflight OPTIONS requests are handled.
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];
        $behaviors['authenticator'] = [
                'class' => HttpBearerAuth::class,
        ];
        // Limit authenticator to write actions; skip OPTIONS so preflight isn't authenticated.
        $behaviors['authenticator']['only'] = [
                'create',
                'update',
                'delete'
            ];
           
        $behaviors['authenticator']['except'] = ['options'];

        // Ensure allowed HTTP verbs and allow OPTIONS for preflight
        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::class,
            'actions' => [
                'index' => ['GET', 'HEAD'],
                'view' => ['GET', 'HEAD'],
                'create' => ['POST'],
                'update' => ['PUT', 'PATCH'],
                'delete' => ['DELETE'],
                'options' => ['OPTIONS'],
            ],
        ];

        return $behaviors;
    }
}