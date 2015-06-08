<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Redirects;

/**
 * RedirectsSearch represents the model behind the search form about `app\models\Redirects`.
 */
class RedirectsSearch extends Redirects
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['click_id', 'subid1', 'subid2', 'subid3', 'subid4', 'subid5', 'keyword', 'date_created', 'referrer', 'country'], 'safe'],
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
        $query = Redirects::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'date_created' => $this->date_created,
        ]);

        $query->andFilterWhere(['like', 'click_id', $this->click_id])
            ->andFilterWhere(['like', 'subid1', $this->subid1])
            ->andFilterWhere(['like', 'subid2', $this->subid2])
            ->andFilterWhere(['like', 'subid3', $this->subid3])
            ->andFilterWhere(['like', 'subid4', $this->subid4])
            ->andFilterWhere(['like', 'subid5', $this->subid5])
            ->andFilterWhere(['like', 'keyword', $this->keyword])
            ->andFilterWhere(['like', 'referrer', $this->referrer])
            ->andFilterWhere(['like', 'country', $this->country]);

        return $dataProvider;
    }
}
