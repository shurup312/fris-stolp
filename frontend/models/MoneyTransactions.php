<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%money_transactions}}".
 *
 * @property integer $id
 * @property integer $money_id
 * @property string  $sum
 * @property string  $balance
 * @property string  $date_created
 * @property string  $comment
 *
 * @property Moneys  $money
 */
class MoneyTransactions extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName () {
        return '{{%money_transactions}}';
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
            [['money_id'], 'integer'],
            [['sum', 'balance'], 'number'],
            [['date_created'], 'safe'],
            [['comment'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels () {
        return [
            'id'           => 'ID транзакции',
            'money_id'     => 'ID кошелька, по которому была транзакция',
            'sum'          => 'Сумма транзакции',
            'balance'      => 'Баланс после транзакции',
            'date_created' => 'Дата транзакции',
            'comment'      => 'Комментарий',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMoney () {
        return $this->hasOne(Moneys::className(), ['id' => 'money_id']);
    }
}
