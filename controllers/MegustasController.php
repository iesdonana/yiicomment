<?php

namespace app\controllers;

use Yii;
use app\models\Megustas;
use app\models\MegustasSearch;
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
     * Lists all Megustas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MegustasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Megustas model.
     * @param integer $usuario_id
     * @param integer $comentario_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($usuario_id, $comentario_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($usuario_id, $comentario_id),
        ]);
    }

    /**
     * Creates a new Megustas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($comentario_id)
    {
        $model = new Megustas([
            'comentario_id' => $comentario_id,
            'usuario_id' => Yii::$app->user->id
        ]);

        $model->save();

        Yii::$app->session->setFlash('success', 'Se ha publicado tu Like');
        return $this->goHome();
    }

    /**
     * Updates an existing Megustas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $usuario_id
     * @param integer $comentario_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($usuario_id, $comentario_id)
    {
        $model = $this->findModel($usuario_id, $comentario_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'usuario_id' => $model->usuario_id, 'comentario_id' => $model->comentario_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Megustas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $usuario_id
     * @param integer $comentario_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($comentario_id)
    {
        $model = Megustas::find()->andWhere([
            'comentario_id' => $comentario_id,
            'usuario_id' => Yii::$app->user->id,
        ])->one();

        if ($model) {
            $model->delete();
            return $this->goHome();
        } else {
            return Yii::$app->session->setFlash('success', 'Ha ocurrido un error.');
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
