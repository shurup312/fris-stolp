<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%moneys}}".
 *
 * @property integer             $id
 * @property integer             $user_id
 * @property string              $balance
 *
 * @property Users               $user
 * @property MoneyTransactions[] $moneyTransactions
 */
class Moneys extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName () {
        return '{{%moneys}}';
    }

    /**
     * @inheritdoc
     */
    public function rules () {
        return [
            [['user_id'], 'integer'],
            [['balance'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels () {
        return [
            'id'      => 'ID кошелька',
            'user_id' => 'ID пользователя, которому принадлежит кошелек',
            'balance' => 'Баланс',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser () {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMoneyTransactions () {
        return $this->hasMany(MoneyTransactions::className(), ['money_id' => 'id']);
    }

    /**
     * @param $userID
     * @return static
     */
    public static function getByUserID ($userID) {
        return Moneys::find()->where('user_id = :user_id', [':user_id' => $userID])->one();
    }
}
