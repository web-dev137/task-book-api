<?php

namespace app\models;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * @property int $id
 * @property string $login
 * @property string $password_hash
 * @property string $auth_key
 * @property bool $is_archived
 * @property int $created_at
 * @property int $updated_at
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    public function rules()
    {
        return [
            [['login','password_hash'],'required'],
            [['created_at','updated_at'], 'safe'],
            [['login','password_hash','auth_key'],'string'],
            ['id','integer']
        ];
    }  

  public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne(["id"=>$id]);
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        try {
            $decoded = JWT::decode(
                jwt: $token, 
                keyOrKeyArray: new Key(
                    keyMaterial: Yii::$app->params['jwtSecretKey'], 
                    algorithm: Yii::$app->params['jwtAlgorithm']
                )
            );

            return self::findOne(['id' => $decoded->data->userId]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return User
     */
    public static function findByUsername(string $login): User
    {
        return self::findOne(["login" => $login]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): ?bool
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password,$this->password_hash);
    }

    /**
     *  Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password_hash = \Yii::$app->security->generatePasswordHash($password);
    }

    public function fields()
    {
        return [
            'id',
            'login',
            'created_at' => function() {
                return date(DATE_RFC3339,$this->created_at);
            }
        ];
    }
}
