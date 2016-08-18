<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class CommentForm extends Model
{
    public $comment;
    public $rating;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['comment'], 'required'],
            [['rating'], 'number'],
            ['comment', 'checkRating'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function checkRating($attribute)
    {
        if (!$this->rating)
            $this->addError($attribute, Yii::t('comment', 'need_votes'. $this->rating));
    }

    public function attributeLabels()
    {
        return [
            'comment' => Yii::t('app', 'comment'),
            'rating' => Yii::t('app', 'rating'),
        ];
    }
}
