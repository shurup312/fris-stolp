<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%redirects}}".
 *
 * @property integer $id
 * @property string  $click_id
 * @property string  $subid1
 * @property string  $subid2
 * @property string  $subid3
 * @property string  $subid4
 * @property string  $subid5
 * @property string  $keyword
 * @property string  $date_created
 * @property string  $referrer
 * @property string  $country
 * @property integer $user_id
 * @property string  $landing
 * @property integer $publisher_soft_id
 *
 * @property Users   $user
 */
class Redirects extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName () {
        return '{{%redirects}}';
    }

    public function behaviors () {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_created',
                'updatedAtAttribute' => false,
                'value'              => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules () {
        return [
            [['date_created'], 'safe'],
            [['user_id', 'publisher_soft_id'], 'integer'],
            [['click_id', 'subid1', 'subid2', 'subid3', 'subid4', 'subid5', 'keyword'], 'string', 'max' => 255],
            [['referrer', 'country', 'landing'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels () {
        return [
            'id'                => 'ID',
            'click_id'          => 'Уникальный идентификатор перехода',
            'subid1'            => 'SubID#1',
            'subid2'            => 'SubID#2',
            'subid3'            => 'SubID#3',
            'subid4'            => 'SubID#4',
            'subid5'            => 'SubID#5',
            'keyword'           => 'Keyword',
            'date_created'      => 'Дата перехода',
            'referrer'          => 'URL, откуда пришел человек.',
            'country'           => 'Город пользователя.',
            'user_id'           => 'ID паблишера',
            'landing'           => 'Landing',
            'publisher_soft_id' => 'ID пользовательского софта для установки',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser () {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    public static function getByClickID ($clickID) {
   		return Redirects::find()->where('click_id=:click_id', [':click_id' => $clickID])->one();
   	}
}
