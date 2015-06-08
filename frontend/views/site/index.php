<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 08.06.15
 * Time: 19:09
 */
use app\models\Phones;

/**
 * @var array $phones
 */

foreach ($phones as $item) {
	/**
	 * @var Phones $item
	 */
	?>
	<div class="tovar">
		<?=$item->name;?><br/>
		<?=$item->price;?> руб. <br/>
		<img src="<?= $item->getPhoto(); ?>" alt="<?= $item->name; ?>"/><br/>
		<a href="/details/<?=$item->id;?>">Детали</a>
	</div>
	<?
}
?>
<style type="text/css">
	.tovar img{
		height: 150px;
		max-width: 100%;
		margin: 3ex 0;
	}
	.tovar a{
		display: block;
		width: 80%;
		background: #33ff33;
		margin: 0 auto;
		padding: 1ex 0;
		text-decoration: none;
		cursor: pointer;
		color:#000000;
	}
	.tovar {
		width:15%;
		padding: 1% 1%;
		margin: 1% 1%;
		border:1px dotted black;
		float: left;
		font-size: 2ex;
		text-align: center;
		height:250px;
	}
</style>
