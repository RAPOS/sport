<?php

namespace app\controllers;

use app\models\Blog;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class BlogController extends Controller
{
    public function actionIndex()
    {
        $query = Blog::find()->orderBy('date DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

}
