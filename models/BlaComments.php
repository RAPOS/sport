<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bla_comments".
 *
 * @property integer $id
 * @property integer $object_id
 * @property string $comment
 * @property integer $rating
 * @property integer $status
 */
class BlaComments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bla_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id', 'comment', 'rating', 'status'], 'required'],
            [['object_id', 'rating', 'status'], 'integer'],
            [['comment'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object_id' => 'Object ID',
            'comment' => Yii::t('app', 'comment'),
            'rating' => Yii::t('app', 'rating'),
            'status' => Yii::t('app', 'status'),
        ];
    }
}
