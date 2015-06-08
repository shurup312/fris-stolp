<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%countries}}".
 *
 * @property integer $id
 * @property string $short_code
 * @property string $eng_name
 * @property string $rus_name
 *
 * @property OfferPrices[] $offerPrices
 */
class Countries extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%countries}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['short_code'], 'string', 'max' => 2],
            [['eng_name', 'rus_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'short_code' => 'Short Code',
            'eng_name' => 'Eng Name',
            'rus_name' => 'Rus Name',
        ];
    }

    public static function getByShortCode ($country_code) {
        return self::find()->where('short_code = :short_code', [':short_code' => $country_code])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferPrices()
    {
        return $this->hasMany(OfferPrices::className(), ['country_id' => 'id']);
    }
}
