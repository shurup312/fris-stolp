<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%landings}}".
 *
 * @property integer              $id
 * @property string               $url
 * @property string               $date_created
 * @property integer              $is_available
 *
 * @property LandingAntiviruses[] $landingAntiviruses
 * @property LandingLanguages[]   $landingLanguages
 * @property ProfitStats[]        $profitStats
 * @property Users[]              $users
 */
class Landings extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return '{{%landings}}';
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
            [['url'], 'required'],
            [['date_created'], 'safe'],
            [['is_available'], 'integer'],
            [['url'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'           => 'ID',
			'url'          => 'URL лэндинга',
			'date_created' => 'Дата создания',
			'is_available' => 'Is Available',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLandingAntiviruses () {
		return $this->hasMany(LandingAntiviruses::className(), ['landing_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLandingLanguages () {
		return $this->hasMany(LandingLanguages::className(), ['landing_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProfitStats () {
		return $this->hasMany(ProfitStats::className(), ['landing_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers () {
		return $this->hasMany(Users::className(), ['id' => 'user_id'])->viaTable(
			'{{%profit_stats}}',
			['landing_id' => 'id']
		);
	}
}
