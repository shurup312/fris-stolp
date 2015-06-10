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
 * @var array $likes
 */
?>
<link rel="stylesheet" href="/bootstrap/dist/css/bootstrap.css"/>
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
	<a href="/" class="btn btn-warning">Сбросить</a>
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
		<? if(isset($likes[$item->id])) {
			?><a href="/site/dellike/?id=<?= $item->id; ?>">
			Отменить <?=$likes[$item->id]==1?'<span class="glyphicon glyphicon-hand-up"></span>':'<span class="glyphicon glyphicon-hand-down"></span>';?>
			<br/></a><?
		} else {
			?><a href="/site/like/?id=<?= $item->id; ?>"><span class="glyphicon glyphicon-hand-up"></span> Нравится</a>&nbsp;&nbsp;<?
			?><a href="/site/dislike/?id=<?= $item->id; ?>"><span class="glyphicon glyphicon-hand-down"></span> Не нравится <br/></a><?
		};?>

		<a href="/details/<?= $item->id; ?>" class="btn btn-info">Детали</a>
	</div>
<?
}
?>
</div>
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
		width: 70%;
	}
	.filter{
		float:left;
		width:33%;
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
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script type="text/javascript">

</script>
