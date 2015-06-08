<?php

namespace app\models\search;

use app\components\interfaces\SearchModelInterface;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Offers;

/**
 * OffersSearch represents the model behind the search form about `app\models\Offers`.
 */
class OffersSearch extends Offers implements SearchModelInterface
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'safe'],
            [['is_fix_countries', 'is_partial_install'], 'boolean'],
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
        $query = Offers::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'is_fix_countries' => $this->is_fix_countries,
            'is_partial_install' => $this->is_partial_install,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
