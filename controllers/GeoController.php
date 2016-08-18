<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 30.07.2016
 * Time: 18:16
 */

namespace app\controllers;

use app\models\SignupForm;
use Yii;
use yii\web\Controller;

class GeoController extends Controller
{
    public function actionSetCountry()
    {
        /* @var $model SignupForm */
        $model = Yii::$app->request->post('model');
        $model = new $model;
        $model->scenario = Yii::$app->request->post('scenario');
        $model->load(Yii::$app->request->post());
        $model->city = null;

        return $this->render(
            Yii::$app->request->post('form'),
            [
                'model' => $model
            ]
        );
    }

    public function actionSetCity()
    {
        /* @var $model SignupForm */
        $model = Yii::$app->request->post('model');
        $model = new $model;
        $model->scenario = Yii::$app->request->post('scenario');
        $model->load(Yii::$app->request->post());

        return $this->render(
            Yii::$app->request->post('form'),
            [
                'model' => $model
            ]
        );
    }
}