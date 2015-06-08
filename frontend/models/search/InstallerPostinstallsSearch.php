<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InstallerPostinstalls;

/**
 * InstallerPostinstallsSearch represents the model behind the search form about `app\models\InstallerPostinstalls`.
 */
class InstallerPostinstallsSearch extends InstallerPostinstalls
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'soft_id'], 'integer'],
            [['click_id', 'soft_name', 'guid', 'hdd_serial_number', 'date_created'], 'safe'],
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
        $query = InstallerPostinstalls::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'soft_id' => $this->soft_id,
            'date_created' => $this->date_created,
        ]);

        $query->andFilterWhere(['like', 'click_id', $this->click_id])
            ->andFilterWhere(['like', 'soft_name', $this->soft_name])
            ->andFilterWhere(['like', 'guid', $this->guid])
            ->andFilterWhere(['like', 'hdd_serial_number', $this->hdd_serial_number]);

        return $dataProvider;
    }
}
