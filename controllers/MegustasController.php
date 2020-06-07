<?php

namespace app\controllers;

use app\models\Comentarios;
use Yii;
use app\models\Megustas;
use app\models\Usuarios;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MegustasController implements the CRUD actions for Megustas model.
 */
class MegustasController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * Comprueba si el usuario ha dado like al comentario, si le ha dado lo elimina y si no lo crea.
     * @return mixed
     */
    public function actionLike($comentario_id)
    {
        $model = Megustas::find()->andWhere([
            'comentario_id' => $comentario_id,
            'usuario_id' => Yii::$app->user->id,
        ])->one();

        if ($model) {
            $model->delete();
            return $this->goBack();
        } else {
            $like = new Megustas([
                'comentario_id' => $comentario_id,
                'usuario_id' => Yii::$app->user->id,
            ]);
            $like->save();
            return $this->goBack();
        }
    }

    /**
     * Finds the Megustas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $usuario_id
     * @param integer $comentario_id
     * @return Megustas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($usuario_id, $comentario_id)
    {
        if (($model = Megustas::findOne(['usuario_id' => $usuario_id, 'comentario_id' => $comentario_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
