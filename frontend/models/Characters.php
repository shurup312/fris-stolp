<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Characters model
 *
 * @property integer $id
 * @property string  $name
 */
class Characters extends ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return '{{%characters}}';
	}
}
