<?php

namespace app\modules\api\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * @property-read int $id
 * @property string $title
 * @property string $author
 * @property int $year
 * @property int $user_id
 */
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
            'published_year'=>'year',
            'author'
        ];
    }
}