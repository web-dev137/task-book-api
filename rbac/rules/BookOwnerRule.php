<?php

namespace app\rbac\rules;

use app\modules\api\models\Book;
use Yii;
use yii\base\InvalidCallException;
use yii\rbac\Rule;

class BookOwnerRule extends Rule 
{
    public $name = 'ownBook';

    public function execute($userId, $item, $params): bool 
    {
        /**
         * @var Book
         */
        $model = Book::findOne(['id' => Yii::$app->request->get('id')]);
        return $model->user_id == $userId;
    }
}