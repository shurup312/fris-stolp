<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "installer_connects".
 *
 * @property integer $id
 * @property string  $click_id
 * @property integer $user_id
 * @property string  $soft_name
 * @property string  $guid
 * @property string  $hdd_serial_number
 * @property string  $net_framework_version
 * @property string  $os_version
 * @property integer $count_softs
 * @property string  $date_created
 *
 * @property Users   $user
 */
class InstallerConnects extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName () {
        return 'installer_connects';
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
            [['user_id', 'count_softs'], 'integer'],
            [['date_created'], 'safe'],
            [['click_id', 'soft_name', 'guid', 'hdd_serial_number', 'net_framework_version', 'os_version'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels () {
        return [
            'id'                    => 'ID',
            'click_id'              => 'Уникальный идентификатор перехода',
            'user_id'               => 'ID вебматера, от которого пришел трафик',
            'soft_name'             => 'Имя отданного файла',
            'guid'                  => 'Глобальный уникальный идентификатор',
            'hdd_serial_number'     => 'Серийный номер жесткого диска',
            'net_framework_version' => 'Версия .NET Framework',
            'os_version'            => 'Версия ОС',
            'count_softs'           => 'Количество отданного спонсорского софта',
            'date_created'          => 'Date Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser () {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
