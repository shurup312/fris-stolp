<?php

namespace app\models;

use app\components\behaviors\installers\IsActiveBehavior;
use claudejanz\fileBehavior\FileBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%installers}}".
 *
 * @property integer $id
 * @property string  $version
 * @property string  $api_version
 * @property string  $file_name
 * @property string  $descriptrion
 * @property boolean $is_active
 * @property string  $hash
 * @property string  $date_created
 * @property boolean $is_banned
 */
class Installers extends \yii\db\ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return '{{%installers}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
            [['is_active', 'is_banned'], 'boolean'],
            [['is_active'], 'default', 'value'=>false],
            [['date_created'], 'safe'],
            [['version', 'api_version'], 'string', 'max' => 32],
            [['file_name'], 'file', 'extensions' => '.exe'],
            [['description'], 'string', 'max' => 256]
		];
	}

	public function behaviors () {
		return [
			[
				'class'              => TimestampBehavior::className(),
				'createdAtAttribute' => 'date_created',
				'updatedAtAttribute' => false,
				'value'              => new Expression('NOW()'),
			],
			'file_name' => [
				'class'                 => FileBehavior::className(),
				'paths'                 => \yii::$app->params['installer_upload_dir'].'{id}/',
				'isUrlReplacementActif' => false,
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'           => 'ID',
			'version'      => 'Version',
			'api_version'  => 'Api Version',
			'file_name'    => 'File Name',
			'description'  => 'Description',
			'is_active'    => 'Is Active',
			'date_created' => 'Date Created',
			'is_banned'    => 'Is Banned',
		];
	}

	public function updateIsActive (){
		$this->attachBehavior('is_active', new IsActiveBehavior());
		$this->is_active = true;
		return $this->save();
	}
}
