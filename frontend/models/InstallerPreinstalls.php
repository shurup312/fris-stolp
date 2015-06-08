<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "installer_preinstalls".
 *
 * @property integer $id
 * @property string  $click_id
 * @property integer $user_id
 * @property string  $soft_name
 * @property string  $guid
 * @property string  $hdd_serial_number
 * @property integer $soft_id
 * @property string  $date_created
 *
 * @property Softs   $soft
 * @property Users   $user
 */
class InstallerPreinstalls extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return '{{%installer_preinstalls}}';
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
			[['user_id', 'soft_id'], 'integer'],
			[['date_created'], 'safe'],
			[['click_id', 'soft_name', 'guid', 'hdd_serial_number'], 'string', 'max' => 255],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'                => 'ID',
			'click_id'          => 'Уникальный идентификатор перехода',
			'user_id'           => 'ID вебматера, от которого пришел трафик',
			'soft_name'         => 'Имя отданного файла',
			'guid'              => 'Глобальный уникальный идентификатор',
			'hdd_serial_number' => 'Серийный номер жесткого диска',
			'soft_id'           => 'ID установленной программы',
			'date_created'      => 'Date Created',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSoft () {
		return $this->hasOne(Softs::className(), ['id' => 'soft_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser () {
		return $this->hasOne(Users::className(), ['id' => 'user_id']);
	}
}
