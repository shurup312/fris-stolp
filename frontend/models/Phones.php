<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Phones model
 *
 * @property integer $id
 * @property string  $name
 * @property string  $price
 * @property string  $photo
 */
class Phones extends ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return '{{%phones}}';
	}

	public function getPhoto()
	{
		return $this->photo?$this->photo:'/images/noPhoto.png';
	}
}
