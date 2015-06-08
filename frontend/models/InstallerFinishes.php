<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "installer_finishes".
 *
 * @property integer $id
 * @property string $click_id
 * @property integer $user_id
 * @property string $soft_name
 * @property string $guid
 * @property string $hdd_serial_number
 * @property string $softs_id
 * @property string $date_created
 *
 * @property Users $user
 */
class InstallerFinishes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'installer_finishes';
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
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['date_created'], 'safe'],
            [['click_id', 'soft_name', 'guid', 'hdd_serial_number', 'softs_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'click_id' => 'Уникальный идентификатор перехода',
            'user_id' => 'ID вебматера, от которого пришел трафик',
            'soft_name' => 'Имя отданного файла',
            'guid' => 'Глобальный уникальный идентификатор',
            'hdd_serial_number' => 'Серийный номер жесткого диска',
            'softs_id' => 'ID установленных софтов',
            'date_created' => 'Date Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
