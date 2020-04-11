<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $log_us
 * @property string $nombre
 * @property string $apellido
 * @property string $email
 * @property string $password
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
class Usuarios extends \yii\db\ActiveRecord
{
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
            [['log_us', 'nombre', 'apellido', 'email', 'password'], 'required'],
            [['log_us', 'nombre', 'apellido'], 'string', 'max' => 60],
            [['email', 'img_name'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 20],
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
            'password' => 'Password',
            'url_img' => 'Url Img',
            'img_name' => 'Img Name',
        ];
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
