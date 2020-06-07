<?php

namespace app\controllers;

use Yii;
use app\models\Seguidores;
use app\models\Usuarios;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SeguidoresController implements the CRUD actions for Seguidores model.
 */
class SeguidoresController extends Controller
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
     * Comprueba si esta siguiendo a al usuario, si no lo sigue empieza a seguirlo y si lo sigue deja de seguirlo.
     *
     * @param [type] $seguido_id
     * @return void
     */
    public function actionFollow($seguido_id)
    {
        $model = Seguidores::find()->andWhere([
            'seguido_id' => $seguido_id,
            'seguidor_id' => Yii::$app->user->id,
        ])->one();

        if ($model) {
            $model->delete();
        } else {
            $seguido = new Seguidores([
                'seguido_id' => $seguido_id,
                'seguidor_id' => Yii::$app->user->id
            ]);
            if ($seguido['seguido_id'] == $seguido['seguidor_id']) {
                Yii::$app->session->setFlash('danger', 'Uno no se puede seguir a si mismo.');
                return $this->redirect(['usuarios/view', 'id' => $seguido_id])->send();
            }
            $seguido->save();
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return array_merge([Seguidores::siguiendo($seguido_id)], [Seguidores::find()->where(['seguido_id' => $seguido_id])->count()]);
    }

    /**
     * Finds the Seguidores model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $seguidor_id
     * @param integer $seguido_id
     * @return Seguidores the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($seguidor_id, $seguido_id)
    {
        if (($model = Seguidores::findOne(['seguidor_id' => $seguidor_id, 'seguido_id' => $seguido_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Se usa para comprobar la existencia del seguimeinto con ajax en la vista.
     *
     * @param [type] $seguido_id
     * @return void
     */
    public function siguiendo($seguido_id)
    {
        $seguido = Seguidores::find()->where([
            'seguidor_id' => Yii::$app->user->id,
            'seguido_id' => $seguido_id
        ])->one();

        return $seguido->exists();
    }

    /**
     * Lista los seguidores de un usuario.
     *
     * @param [type] $usuario_id
     * @return void
     */
    public function actionSeguidores($usuario_id)
    {
        $usuario = Usuarios::findOne(['id' => $usuario_id]);
        
        $seguidores = $usuario->getSeguidores0()->select('seguidor_id')->column();

        $usuarios = Usuarios::find()->where(['id' => $seguidores])->all();

        return $this->render('seguidores', [
            'usuarios' => $usuarios,
        ]);
    }

    /**
     * Lista a la gente que sigue un usuario.
     *
     * @param [type] $usuario_id
     * @return void
     */
    public function actionSeguidos($usuario_id)
    {
        $usuarios = Seguidores::find()->where(['seguidor_id' => $usuario_id])->select('seguido_id')->column();
        
        $seguidos = Usuarios::find()->where(['id' => $usuarios])->all();

        return $this->render('seguidos', [
            'usuarios' => $seguidos,
        ]);
    }
}
