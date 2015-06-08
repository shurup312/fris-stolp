<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%landing_antiviruses}}".
 *
 * @property integer     $landing_id
 * @property integer     $antivirus_id
 * @property integer     $status
 * @property string      $date_checked
 *
 * @property Landings    $landing
 * @property Antiviruses $antivirus
 */
class LandingAntiviruses extends \yii\db\ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName () {
        return '{{%landing_antiviruses}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
            [['landing_id', 'antivirus_id'], 'required'],
            [['landing_id', 'antivirus_id', 'status'], 'integer'],
            [['date_checked'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'landing_id'   => 'ID лэндинга',
			'antivirus_id' => 'ID антивируса',
			'status'       => 'Status',
			'date_checked' => 'Date Checked',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLanding () {
		return $this->hasOne(Landings::className(), ['id' => 'landing_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAntivirus () {
		return $this->hasOne(Antiviruses::className(), ['id' => 'antivirus_id']);
	}
}
