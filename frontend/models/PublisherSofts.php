<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%publisher_softs}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string  $name
 * @property integer $offer_id
 * @property string  $filename
 * @property string  $url
 * @property string  $image_url
 * @property string  $execute_params
 * @property string  $final_page_url
 * @property string  $date_created
 */
class PublisherSofts extends ActiveRecord {
    const DEFAULT_LANDING = 5;
    /**
     * @inheritdoc
     */
    public static function tableName () {
        return '{{%publisher_softs}}';
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
            [['offer_id', 'name', 'url', 'image_url', 'filename'], 'required'],
            [['filename'], 'match', 'pattern'=>'/^[0-9a-zA-Z\-\_]+?$/', 'message'=>'File name can only contain symbols "a..z", "-" and "_"'],
            [['user_id'], 'default', 'value'=>\yii::$app->getUser()->getId()],
            [['user_id', 'offer_id'], 'integer'],
            [['url', 'final_page_url'], 'url'],
            [['name', 'url', 'image_url'], 'string', 'max' => 256],
            [['filename', 'execute_params', 'final_page_url', 'date_created'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels () {
        return [
            'id'             => 'ID',
            'user_id'        => 'User ID',
            'name'           => 'Product name',
            'offer_id'       => 'Campaign',
            'filename'       => 'File name',
            'url'            => 'EXE file URL',
            'image_url'      => 'Image file URL',
            'execute_params' => 'Command line',
            'final_page_url' => '"Thank You" Page URL',
            'date_created'   => 'Date created',
        ];
    }
}
