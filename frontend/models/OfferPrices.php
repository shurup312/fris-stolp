<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%offer_prices}}".
 *
 * @property integer   $id
 * @property integer   $offer_id
 * @property integer   $country_id
 * @property string    $price
 *
 * @property Offers    $offer
 * @property Countries $country
 */
class OfferPrices extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName () {
        return '{{%offer_prices}}';
    }

    /**
     * @inheritdoc
     */
    public function rules () {
        return [
            [['offer_id', 'country_id'], 'integer'],
            [['price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels () {
        return [
            'id'         => 'ID записи',
            'offer_id'   => 'ID оффера',
            'country_id' => 'ID страны',
            'price'      => 'Стоимость оффера для паблишера',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffer () {
        return $this->hasOne(Offers::className(), ['id' => 'offer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry () {
        return $this->hasOne(Countries::className(), ['id' => 'country_id']);
    }

    /**
     * @param $offer_id
     * @param $country_code
     * @return bool|OfferPrices
     */
    public static function getByShortCode ($offer_id, $country_code) {
        $country = Countries::getByShortCode($country_code);
        if(!$country) {
            return false;
        }
        $offerPrice = self::find()->where('offer_id = :offer_id', [':offer_id' => $offer_id])->andWhere('country_id = :country_id', [':country_id' => $country['id']])->one();
        if(!$offerPrice) {
            return false;
        }
        return $offerPrice;
    }
}
