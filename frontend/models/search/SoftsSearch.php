<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Softs;

/**
 * SoftsSearch represents the model behind the search form about `app\models\Softs`.
 */
class SoftsSearch extends Softs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'platform_id', 'country_id', 'limit_per_day', 'net_version', 'params', 'view_type'], 'integer'],
            [['date_created', 'date_close', 'url', 'image_url', 'installer_description', 'admin_description'], 'safe'],
            [['is_active'], 'boolean'],
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
        $query = Softs::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'date_created' => $this->date_created,
            'is_active' => $this->is_active,
            'platform_id' => $this->platform_id,
            'country_id' => $this->country_id,
            'limit_per_day' => $this->limit_per_day,
            'date_close' => $this->date_close,
            'net_version' => $this->net_version,
            'params' => $this->params,
            'view_type' => $this->view_type,
        ]);

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'image_url', $this->image_url])
            ->andFilterWhere(['like', 'installer_description', $this->installer_description])
            ->andFilterWhere(['like', 'admin_description', $this->admin_description]);

        return $dataProvider;
    }
}
