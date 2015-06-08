<?php
namespace frontend\controllers;

use common\models\forms\LoginForm;
use Yii;
use frontend\models\forms\PasswordResetRequestForm;
use frontend\models\forms\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends Controller {

	public $layout = 'public';

	public function actionIndex () {
		return $this->renderPartial('index');
	}
}
