<?php

namespace app\controllers;

use Yii;
use app\models\Authentication;
use yii\data\ActiveDataProvider;
use app\components\WebController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthenticationController implements the CRUD actions for Authentication model.
 */
class AuthenticationController extends WebController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Authentication models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Authentication::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Authentication model.
     * @param integer $user_id
     * @param integer $identity_type
     * @return mixed
     */
    public function actionView($user_id, $identity_type)
    {
        return $this->render('view', [
            'model' => $this->findModel($user_id, $identity_type),
        ]);
    }

    /**
     * Creates a new Authentication model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Authentication();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'identity_type' => $model->identity_type]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Authentication model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $user_id
     * @param integer $identity_type
     * @return mixed
     */
    public function actionUpdate($user_id, $identity_type)
    {
        $model = $this->findModel($user_id, $identity_type);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'identity_type' => $model->identity_type]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Authentication model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $user_id
     * @param integer $identity_type
     * @return mixed
     */
    public function actionDelete($user_id, $identity_type)
    {
        $this->findModel($user_id, $identity_type)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Authentication model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $user_id
     * @param integer $identity_type
     * @return Authentication the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id, $identity_type)
    {
        if (($model = Authentication::findOne(['user_id' => $user_id, 'identity_type' => $identity_type])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
