<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "softs".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $date_created
 * @property boolean $is_active
 * @property integer $platform_id
 * @property integer $country_id
 * @property integer $limit_per_day
 * @property string $date_close
 * @property integer $net_version
 * @property string $url
 * @property integer $params
 * @property integer $view_type
 * @property string $image_url
 * @property string $installer_description
 * @property string $admin_description
 *
 * @property Users $user
 * @property InstallerPostinstalls[] $installerPostinstalls
 * @property InstallerPreinstalls[]  $installerPreinstalls
 */
class Softs extends ActiveRecord {

	const NET_FRAMEWORK_20 = 1;
	const NET_FRAMEWORK_30 = 2;
	const NET_FRAMEWORK_35 = 3;
	const NET_FRAMEWORK_40 = 4;
	const NET_FRAMEWORK_45 = 5;
	const NET_FRAMEWORK_NONE =100;

	public static $net_frameworks = [
		'2.0' => self::NET_FRAMEWORK_20,
		'3.0' => self::NET_FRAMEWORK_30,
		'3.5' => self::NET_FRAMEWORK_35,
		'4.0' => self::NET_FRAMEWORK_40,
		'4.5' => self::NET_FRAMEWORK_45,
		'0.0' => self::NET_FRAMEWORK_NONE,
	];
	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return '{{%softs}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
        return [
            [['url', 'image_url', 'net_version', 'net_version', 'view_type'], 'required'],
            [['user_id', 'platform_id', 'country_id', 'limit_per_day', 'net_version', 'view_type'], 'integer'],
            [['date_created', 'date_close'], 'safe'],
            [['is_active'], 'boolean'],
            [['url', 'image_url', 'installer_description', 'admin_description', 'params'], 'string', 'max' => 256]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'                    => 'ID',
			'user_id'               => 'ID рекламодателя',
			'date_created'          => 'Дата создания',
			'is_active'             => 'Активен ли софт',
			'platform_id'           => 'ID платформы',
			'country_id'            => 'ID страны',
			'limit_per_day'         => 'Лимит в день, null - без лимита',
			'date_close'            => 'Дата закрытия, null - дата отсутствует',
			'net_version'           => '.NET Framework version',
			'url'                   => 'URL for upload file',
			'params'                => 'Params for soft execute',
			'view_type'             => 'Тип представления спонсорского софта в инсталлере, 0 - в виде галочки, 1 - в виде отдельного окна.',
			'image_url'             => 'URL for image of soft',
			'installer_description' => 'Description of soft for installer',
			'admin_description'     => 'Soft description for admin',
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
	public function getInstallerPostinstalls () {
		return $this->hasMany(InstallerPostinstalls::className(), ['soft_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstallerPreinstalls () {
		return $this->hasMany(InstallerPreinstalls::className(), ['soft_id' => 'id']);
	}
}
