<?php

namespace app\models;

use Yii;
use yii\helpers\Security;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $log_us
 * @property string $nombre
 * @property string $apellido
 * @property string $email
 * @property string $password
 * @property string $rol
 * @property string|null $auth_key
 * @property string|null $url_img
 * @property string|null $img_name
 *
 * @property Comentarios[] $comentarios
 * @property Megustas[] $megustas
 * @property Comentarios[] $comentarios0
 * @property Seguidores[] $seguidores
 * @property Seguidores[] $seguidores0
 * @property Usuarios[] $seguidos
 * @property Usuarios[] $seguidors
 */
class Usuarios extends \yii\db\ActiveRecord implements IdentityInterface
{
    const SCENARIO_CREAR = 'crear';
    const SCENARIO_UPDATE = 'update';

    public $password_repeat;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['log_us', 'nombre', 'apellido', 'email', 'rol'], 'required'],
            [
                ['password'],
                'required',
                'on' => [self::SCENARIO_DEFAULT, self::SCENARIO_CREAR],
            ],
            [
                ['password'],
                'trim',
                'on' => [self::SCENARIO_CREAR, self::SCENARIO_UPDATE],
            ],
            [['log_us', 'nombre', 'apellido'], 'string', 'max' => 60],
            [['email', 'password', 'rol', 'auth_key', 'img_name'], 'string', 'max' => 255],
            [
                [['password_repeat'], 'compare', 'compareAttribute' => 'password'],
                'required',
                'on' => self::SCENARIO_CREAR
            ],
            [
                ['password_repeat'],
                'compare',
                'compareAttribute' => 'password',
                'skipOnEmpty' => false,
                'on' => [self::SCENARIO_CREAR, self::SCENARIO_UPDATE],
            ],
            [['url_img'], 'string', 'max' => 2048],
            [['email'], 'unique'],
            [['log_us'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'log_us' => 'Log Us',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'email' => 'Email',
            'password' => 'Contraseña',
            'password_repeat' => 'Repetir contraseña',
            'rol' => 'Rol',
            'auth_key' => 'Auth Key',
            'url_img' => 'Url Img',
            'img_name' => 'Img Name',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        // return $this->auth_key === $authKey;
    }

    public static function findByUsername($nombre)
    {
        return static::findOne(['nombre' => $nombre]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            if ($this->scenario === self::SCENARIO_CREAR) {
                $security = Yii::$app->security;
                $this->auth_key = $security->generateRandomString();
                $this->password = $security->generatePasswordHash($this->password);
            }
        } else {
            if ($this->scenario === self::SCENARIO_UPDATE) {
                if ($this->password === '') {
                    $this->password = $this->getOldAttribute('password');
                } else {
                    $this->password = $security->generatePasswordHash($this->password);
                }
            }
        }

        return true;
    }

    /**
     * Gets query for [[Comentarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(Comentarios::className(), ['usuario_id' => 'id']);
    }

    /**
     * Gets query for [[Megustas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMegustas()
    {
        return $this->hasMany(Megustas::className(), ['usuario_id' => 'id']);
    }

    /**
     * Gets query for [[Comentarios0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios0()
    {
        return $this->hasMany(Comentarios::className(), ['id' => 'comentario_id'])->viaTable('megustas', ['usuario_id' => 'id']);
    }

    /**
     * Gets query for [[Seguidores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeguidores()
    {
        return $this->hasMany(Seguidores::className(), ['seguidor_id' => 'id']);
    }

    /**
     * Gets query for [[Seguidores0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeguidores0()
    {
        return $this->hasMany(Seguidores::className(), ['seguido_id' => 'id']);
    }

    /**
     * Gets query for [[Seguidos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeguidos()
    {
        return $this->hasMany(Usuarios::className(), ['id' => 'seguido_id'])->viaTable('seguidores', ['seguidor_id' => 'id']);
    }

    /**
     * Gets query for [[Seguidors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeguidors()
    {
        return $this->hasMany(Usuarios::className(), ['id' => 'seguidor_id'])->viaTable('seguidores', ['seguido_id' => 'id']);
    }
}
