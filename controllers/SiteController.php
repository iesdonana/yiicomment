<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Comentarios;
use app\models\Megustas;
use app\models\Usuarios;
use yii\data\Pagination;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'login', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Te enseña los comentarios del usuario y los comentarios de los usuarios a los que sigues.
     *
     * @return string
     */
    public function actionIndex()
    {
        $idActual = Yii::$app->user->id;

        $model = Usuarios::findOne(['id' => $idActual]);

        $ids = $model->getSeguidos()->select('id')->column();
        
        array_push($ids, $idActual);

        $query = Comentarios::find()->where(['IN', 'usuario_id', $ids])->orderBy(['created_at' => SORT_DESC]);

        $count = $query->count();

        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => 5
        ]);

        $comentarios = $query->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

        $publicar = new Comentarios(['usuario_id' => $idActual]);

        if ($publicar->load(Yii::$app->request->post()) && $publicar->save()) {
            Yii::$app->session->setFlash('success', 'Se ha publicado tu comentario.');
            return $this->redirect('index');
        }

        return $this->render('index', [
            'comentarios' => $comentarios,
            'publicar' => $publicar,
            'pagination' => $pagination
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionCookie()
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => 'aceptar',
            'value' => '1',
            'expire' => time() + 3600 * 24 * 365,
            'domain' => '',
        ]));
        return $this->goBack();
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
