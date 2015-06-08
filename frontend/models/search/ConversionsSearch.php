<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Conversions;

/**
 * ConversionsSearch represents the model behind the search form about `app\models\Conversions`.
 */
class ConversionsSearch extends Conversions {

    /**
     * @inheritdoc
     */
    public function rules () {
        return [
            [['id', 'offer_id', 'objective_id', 'user_id', 'approve_status', 'ip'], 'integer'],
            [['click_id', 'subid1', 'subid2', 'subid3', 'subid4', 'subid5', 'keyword', 'hold_status', 'user_agent', 'date_created', 'date_closed', 'comment', 'unique_id'], 'safe'],
            [['network_price', 'publisher_price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios () {
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
    public function search ($params) {
        $query        = Conversions::find();
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
            ]
        );
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(
            [
                'id'              => $this->id,
                'offer_id'        => $this->offer_id,
                'objective_id'    => $this->objective_id,
                'user_id'         => $this->user_id,
                'approve_status'  => $this->approve_status,
                'network_price'   => $this->network_price,
                'publisher_price' => $this->publisher_price,
                'ip'              => $this->ip,
                'date_created'    => $this->date_created,
                'date_closed'     => $this->date_closed,
            ]
        );

        $query->andFilterWhere(['like', 'click_id', $this->click_id])
            ->andFilterWhere(['like', 'subid1', $this->subid1])
            ->andFilterWhere(['like', 'subid2', $this->subid2])
            ->andFilterWhere(['like', 'subid3', $this->subid3])
            ->andFilterWhere(['like', 'subid4', $this->subid4])
            ->andFilterWhere(['like', 'subid5', $this->subid5])
            ->andFilterWhere(['like', 'keyword', $this->keyword])
            ->andFilterWhere(['like', 'hold_status', $this->hold_status])
            ->andFilterWhere(['like', 'user_agent', $this->user_agent])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'unique_id', $this->unique_id]);

        return $dataProvider;
    }
}
