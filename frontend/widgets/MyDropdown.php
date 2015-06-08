<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 10.02.2015
 * Time: 18:00
 *
 * Весь гемор с этим классом сделан потому, что yii2 подтягивает свой bootstrap.js из за виджета, и он затирает
 * js файл темы, тем самым тема перестает работать.
 */
namespace app\widgets;

use yii\bootstrap\Dropdown;

class MyDropdown extends Dropdown {

	/**
	 * Renders the widget.
	 */
	public function run () {
		echo $this->renderItems($this->items, $this->options);
	}
}
