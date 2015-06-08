<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Installers;

/**
 * InstallersSearch represents the model behind the search form about `app\models\Installers`.
 */
class InstallersSearch extends Installers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['version', 'api_version', 'file_name', 'descriptrion', 'hash', 'date_created'], 'safe'],
            [['is_active', 'is_banned'], 'boolean'],
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
        $query = Installers::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'is_active' => $this->is_active,
            'date_created' => $this->date_created,
            'is_banned' => $this->is_banned,
        ]);

        $query->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'api_version', $this->api_version])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'descriptrion', $this->descriptrion])
            ->andFilterWhere(['like', 'hash', $this->hash]);

        return $dataProvider;
    }
}
