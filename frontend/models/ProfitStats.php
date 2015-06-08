<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profit_stats".
 *
 * @property integer $user_id
 * @property integer $landing_id
 * @property string $amount
 *
 * @property Users $user
 * @property Landings $landing
 */
class ProfitStats extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profit_stats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'landing_id'], 'required'],
            [['user_id', 'landing_id'], 'integer'],
            [['amount'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'ID паблишера',
            'landing_id' => 'ID лэндинга',
            'amount' => 'Значение',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanding()
    {
        return $this->hasOne(Landings::className(), ['id' => 'landing_id']);
    }
}
