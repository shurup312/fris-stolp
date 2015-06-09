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
 * @var array $filterList
 * @var array $filterList
 */
?>
<link rel="stylesheet" href="/vendor/dist/css/bootstrap.css"/>
<div class="center filter-form">
	<form action="" method="post">

	<?
	foreach ($filterList as $id => $select) {
		?>
		<div class="filter">
			<h4><?= $select['name']; ?></h4>
			<select name="filter[<?= $id; ?>]" id="">
				<option value="">--</option>
				<? foreach ($select['params'] as $option) {
					?>
					<option value='<?= $option; ?>' <?=isset($_POST['filter']) && isset($_POST['filter'][$id]) && $_POST['filter'][$id] == $option?'selected="selected"':'';?>><?= $option; ?></option><?
				}?>
			</select>
		</div>
	<?
	}
	?>
	<div class="clearfix"></div>
	<br/>
	<input type="submit" value="Искать" class="btn btn-primary" />
	</form>
</div>
<div class="listAll"><?
foreach ($phones as $item) {
	/**
	 * @var Phones $item
	 */
	?>
	<div class="col-md-4 tovar">
		<strong><?= $item->name; ?></strong><br/>
		<?= $item->price; ?> руб. <br/>
		<img src="<?= $item->getPhoto(); ?>" alt="<?= $item->name; ?>" class=""/><br/>
		<a href="javascript:void(0);"><span class="glyphicon glyphicon-hand-up"></span> Нравится</a>
		<a href="javascript:void(0);"><span class="glyphicon glyphicon-hand-down"></span> Не нравится <br/></a>
		<a href="/details/<?= $item->id; ?>" class="btn btn-info">Детали</a>
	</div>
<?
}
?></div>
<style type="text/css">
	.tovar img {
		height: 50%;
		max-width: 100%;
		margin: 3ex 0;
	}

	.tovar {
		padding: 1% 1%;
		border: 1px dotted black;
		font-size: 2ex;
		text-align: center;
		height: 320px;
	}
	.filter select{
		width: 80%;
	}
	.filter{
		float:left;
		width:20%;
		text-align: left;
	}
	.clearfix {
		clear:both;
	}
	.center {
		text-align: center;
	}

	.filter-form {
		margin:0 auto;
		width: 1200px;
	}

	.listAll {
		width: 1200px;
		margin: 0 auto;
	}
</style>
