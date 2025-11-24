<?php

namespace app\modules\api\controllers;

use app\modules\api\models\Book;
use Yii;
use yii\filters\AccessControl;
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
    
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['create', 'update', 'delete'],
            'rules' => [
                [
                    'actions' => ['update','delete'],
                    'allow' => true,
                    'roles' => ['ownBook']
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['@'],
                ]
            ]
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