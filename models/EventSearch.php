<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * EventSearch represents the model behind the search form about `app\models\Event`.
 */
class EventSearch extends EventForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'date_start', 'date_end', 'count_place', 'free_count_place', 'min_count_place', 'max_count_place', 'coach', 'duration', 'count_views', 'recalculate_price', 'status', 'city_id', 'event_type', 'place_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            [['description', 'constantly_day', 'constantly_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = EventForm::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'price' => $this->price,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'count_place' => $this->count_place,
            'free_count_place' => $this->free_count_place,
            'min_count_place' => $this->min_count_place,
            'max_count_place' => $this->max_count_place,
            'coach' => $this->coach,
            'duration' => $this->duration,
            'count_views' => $this->count_views,
            'recalculate_price' => $this->recalculate_price,
            'status' => $this->status,
            'city_id' => $this->city_id,
            'event_type' => $this->event_type,
            'place_id' => $this->place_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'constantly_day', $this->constantly_day])
            ->andFilterWhere(['like', 'constantly_time', $this->constantly_time]);

        return $dataProvider;
    }
}
