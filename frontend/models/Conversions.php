<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%conversions}}".
 *
 * @property integer $id
 * @property string  $click_id
 * @property integer $offer_id
 * @property integer $objective_id
 * @property integer $user_id
 * @property string  $subid1
 * @property string  $subid2
 * @property string  $subid3
 * @property string  $subid4
 * @property string  $subid5
 * @property string  $keyword
 * @property integer $approve_status
 * @property integer $hold_status
 * @property string  $network_price
 * @property string  $publisher_price
 * @property string  $user_agent
 * @property string  $date_created
 * @property string  $date_closed
 * @property string  $comment
 * @property string  $unique_id
 * @property string  $ip
 * @property string  $landing
 * @property string  $country
 */
class Conversions extends ActiveRecord {

    const APPROVE_STATUS_WAIT = 1;
    const APPROVE_STATUS_APPROVED = 2;
    const APPROVE_STATUS_REJECTED = 3;
    const HOLD_STATUS_OPEN = 1;
    const HOLD_STATUS_CLOSE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName () {
        return '{{%conversions}}';
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
            [['offer_id', 'objective_id', 'user_id', 'approve_status', 'hold_status', 'ip'], 'integer'],
            [['network_price', 'publisher_price'], 'number'],
            [['user_agent'], 'string'],
            [['date_created', 'date_closed'], 'safe'],
            [['click_id'], 'string', 'max' => 32],
            [['subid1', 'subid2', 'subid3', 'subid4', 'subid5', 'keyword', 'comment', 'landing'], 'string', 'max' => 256],
            [['unique_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels () {
        return [
            'id'              => 'ID транзакции',
            'click_id'        => 'ID клика',
            'offer_id'        => 'ID оффера',
            'objective_id'    => 'ID цели',
            'user_id'         => 'ID паблишера',
            'subid1'          => 'SubID#1',
            'subid2'          => 'SubID#2',
            'subid3'          => 'SubID#3',
            'subid4'          => 'SubID#4',
            'subid5'          => 'SubID#5',
            'keyword'         => 'Ключевое слово',
            'approve_status'  => 'Статус подтверждения',
            'hold_status'     => 'Статус холда',
            'network_price'   => 'Цена для рекламодателя',
            'publisher_price' => 'Цена для паблишера',
            'user_agent'      => 'User-Agent',
            'date_created'    => 'Дата создания',
            'date_closed'     => 'дата обработки',
            'comment'         => 'Комментарий',
            'unique_id'       => 'Уникальный идентификатор конверсии, который позволяет дублирующую конверсию.',
            'ip'              => 'Ip',
            'landing'         => 'Landing',
            'country'         => 'Country',
        ];
    }

    public static function getByUniqueIDAfterDate ($uniqueID, $date) {
        return self::find()
            ->where("date_created>:date_created", [':date_created' => $date])
            ->andWhere('unique_id=:unique_id',['unique_id' => $uniqueID])
            ->one();
    }
}
