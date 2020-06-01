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
     * Updates an existing Seguidores model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $seguidor_id
     * @param integer $seguido_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($seguidor_id, $seguido_id)
    {
        $model = $this->findModel($seguidor_id, $seguido_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'seguidor_id' => $model->seguidor_id, 'seguido_id' => $model->seguido_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
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

    public function siguiendo($seguido_id)
    {
        $seguido = Seguidores::find()->where([
            'seguidor_id' => Yii::$app->user->id,
            'seguido_id' => $seguido_id
        ])->one();

        return $seguido->exists();
    }

    public function actionSeguidores($usuario_id)
    {
        $usuario = Usuarios::findOne(['id' => $usuario_id]);
        
        $seguidores = $usuario->getSeguidores0()->select('seguidor_id')->column();

        $usuarios = Usuarios::find()->where(['id' => $seguidores])->all();

        return $this->render('view', [
            'usuarios' => $usuarios,
        ]);
    }
}
