<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "megustas".
 *
 * @property int $usuario_id
 * @property int $comentario_id
 *
 * @property Comentarios $comentario
 * @property Usuarios $usuario
 */
class Megustas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'megustas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id', 'comentario_id'], 'required'],
            [['usuario_id', 'comentario_id'], 'default', 'value' => null],
            [['usuario_id', 'comentario_id'], 'integer'],
            [['usuario_id', 'comentario_id'], 'unique', 'targetAttribute' => ['usuario_id', 'comentario_id']],
            [['comentario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comentarios::className(), 'targetAttribute' => ['comentario_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usuario_id' => 'Usuario ID',
            'comentario_id' => 'Comentario ID',
        ];
    }

    /**
     * Gets query for [[Comentario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComentario()
    {
        return $this->hasOne(Comentarios::className(), ['id' => 'comentario_id']);
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id']);
    }
}
