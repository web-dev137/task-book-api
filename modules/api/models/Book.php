<?php

namespace app\modules\api\models;

use Yii;
use yii\behaviors\TimestampBehavior;

class Book extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'book';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        $this->user_id = Yii::$app->user->getId();
        return true;
    }

    public function rules()
    {
        return [
            [['title', 'author', 'year'], 'required'],
            ['user_id','safe'],
            [['year'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'title',
            'author',
            'year',
            'author'
        ];
    }
}