<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InstallerConnects;

/**
 * InstallerConnectsSearch represents the model behind the search form about `app\models\InstallerConnects`.
 */
class InstallerConnectsSearch extends InstallerConnects
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'count_softs'], 'integer'],
            [['click_id', 'soft_name', 'guid', 'hdd_serial_number', 'net_framework_version', 'os_version', 'date_created'], 'safe'],
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
        $query = InstallerConnects::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'count_softs' => $this->count_softs,
            'date_created' => $this->date_created,
        ]);

        $query->andFilterWhere(['like', 'click_id', $this->click_id])
            ->andFilterWhere(['like', 'soft_name', $this->soft_name])
            ->andFilterWhere(['like', 'guid', $this->guid])
            ->andFilterWhere(['like', 'hdd_serial_number', $this->hdd_serial_number])
            ->andFilterWhere(['like', 'net_framework_version', $this->net_framework_version])
            ->andFilterWhere(['like', 'os_version', $this->os_version]);

        return $dataProvider;
    }
}
