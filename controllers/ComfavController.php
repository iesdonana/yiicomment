<?php

namespace app\controllers;

use Yii;
use app\models\comfav;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Comentarios;
use app\models\Seguidores;
use app\models\Usuarios;
use yii\data\Pagination;

class ComfavController extends Controller
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
     * Lista todos los comentarios favoritos del usuario.
     *
     * @param [type] $id
     * @return void
     */
    public function actionView($id)
    {
        $model = Usuarios::findOne(['id' => $id]);

        $likes = comfav::find()->where(['usuario_id' => $id])->select('comentario_id')->column();

        $query = Comentarios::find()->where(['id' => $likes])->orderBy(['created_at' => SORT_DESC]);

        $count = $query->count();

        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => 5
        ]);

        $comentarios = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $seguidores = Seguidores::find()->where(['seguido_id' => $id])->all();
        $seguidos = Seguidores::find()->where(['seguidor_id' => $id])->all();

        $num_segr = count($seguidores);
        $num_sego = count($seguidos);

        return $this->render('view', [
            'model' => $model,
            'seguido_id' => $id,
            'num_segr' => $num_segr,
            'num_sego' => $num_sego,
            'comentarios' => $comentarios,
            'pagination' => $pagination,
            'likes' => $likes
        ]);
    }

    /**
     * Comprueba si el usuario actual tiene el comentario guardado, si lo tiene lo elimina y si no lo crea.
     *
     * @param [type] $comentario_id
     * @return void
     */
    public function actionComfav($comentario_id)
    {
        $model = comfav::find()->andWhere([
            'comentario_id' => $comentario_id,
            'usuario_id' => Yii::$app->user->id,
        ])->one();

        if ($model) {
            $model->delete();
            return $this->goBack();
        } else {
            $like = new comfav([
                'comentario_id' => $comentario_id,
                'usuario_id' => Yii::$app->user->id,
            ]);
            $like->save();
            return $this->goBack();
        }
    }

    /**
     * Finds the comfav model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $usuario_id
     * @param integer $comentario_id
     * @return comfav the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($usuario_id, $comentario_id)
    {
        if (($model = comfav::findOne(['usuario_id' => $usuario_id, 'comentario_id' => $comentario_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
