<?php
namespace app\models\forms;

use app\models\Users;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model {

	public $username;
	public $email;
	public $password;
	public $password2;
	public $iAgree;

	/**
	 * TODO: сделать валидацию подтверждения пароля и галочки "я согласен"
	 */

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
			['username', 'filter', 'filter' => 'trim'],
			['username', 'required'],
			['username', 'unique', 'targetClass' => 'app\models\Users', 'message' => 'Это имя пользователя уже используется.'],
			['username', 'string', 'min' => 3, 'max' => 255],

			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'unique', 'targetClass' => 'app\models\Users', 'message' => 'Этот адрес электронной почты уже используется.'],

			['password', 'required'],
			['password', 'string', 'min' => 6],

			['password2', 'required'],
			['password2', 'validatePassword2'],

			['iAgree', 'required', 'message' => 'You must to agree out terms of services and privacy policy.'],
		];
	}

	public function validatePassword2 ($attribute, $config) {
		if($this->password !== $this->password2) {
			$this->addError($attribute, 'Passwords must be the same.');
		}
	}

	/**
	 * Signs user up.
	 *
	 * @return Users|null the saved model or null if saving fails
	 */
	public function signup () {
		if ($this->validate()) {
			$user = new Users();
			$user->username = $this->username;
			$user->email = $this->email;
			$user->setPassword($this->password);
			$user->generateAuthKey();
			$user->save();


			$auth = Yii::$app->authManager;
			$authorRole = $auth->getRole('publisher');
			$auth->assign($authorRole, $user->getId());
			return $user;
		}
		return null;
	}

	public function attributeLabels () {
		return [
			'username'  => 'Login',
			'password'  => 'Password',
			'email'     => 'E-mail',
			'password2' => 'Password',
		];
	}
}
