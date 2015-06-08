<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 08.06.15
 * Time: 22:49
 */
use app\models\CharactersValues;
use app\models\Phones;

/**
 * @var Phones             $phone
 * @var CharactersValues[] $phoneParams
 */
?>
<style type="text/css">
	.tovar {
		width: 1200px;
		margin: 0 auto;
	}

	.description {
		float: right;
		width: 49%;
	}

	.image {
		float: left;
		width: 49%;
	}

	.image img {
		max-width: 80%;
		max-height: 400px;
		margin: 0 auto;
	}

	.clearfix {
		clear: both;

	}
</style>

<div class="tovar">
	<a href="/">Список телефонов</a> >> <?= $phone->name; ?>
	<div class="clearfix"></div>
	<br/>

	<div class="image">
		<img src="<?= $phone->photo; ?>" alt="<?= $phone->name; ?>"/>
	</div>
	<div class="description">
		<h2><?= $phone->name; ?></h2>
		<h3>Цена: <?= $phone->price; ?></h3>
		<h4>Характеристики:</h4>
		<ul>
			<? foreach ($phoneParams as $param) {
				?>
				<li><?= $param->names->name; ?> : <?=$param->value;?></li><?
			}?>
		</ul>
	</div>
</div>
