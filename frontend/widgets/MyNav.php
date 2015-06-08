<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 10.02.2015
 * Time: 17:58
 *
 * Весь гемор с этим классом сделан потому, что yii2 подтягивает свой bootstrap.js из за виджета, и он затирает
 * js файл темы, тем самым тема перестает работать.
 */
namespace app\widgets;

use yii\bootstrap\Nav;

class MyNav extends Nav {

	/**
	 * @param array $items
	 * @param array $parentItem
	 * @return string
	 */
	protected function renderDropdown ($items, $parentItem) {
		return MyDropdown::widget(
			[
				'items'         => $items,
				'encodeLabels'  => $this->encodeLabels,
				'clientOptions' => false,
				'view'          => $this->getView(),
			]
		);
	}
}
