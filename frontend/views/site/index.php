<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 08.06.15
 * Time: 19:09
 */
use app\models\Phones;
use frontend\controllers\FRIS;

/**
 * @var array $characters
 * @var array $phones
 */
?>
<link rel="stylesheet" href="/bootstrap/dist/css/bootstrap.css"/>

<div class="center filter-form">
	<div class="alert alert-info left">
		Проставьте в одном или более полях оценку важности для Вас параметра.
		Оценка может быть от <?= sizeof($characters); ?> (самый важный параметр) до 1 (наименее значимый параметр).<br/>
		Для примера, если параметр "Встроенная память", важен, а остальные параметры не важны, то
		ставим <?= sizeof($characters); ?> в соответствующее
		поле, а остальные поля оставляем пустыми.
	</div>
	<form action="" method="post">

		<?
		foreach ($characters as $input) {
			?>
			<div class="filter">
				<h4><?= $input['name']; ?></h4>
				<input type="number" min="1" max="<?= sizeof($characters); ?>" name="filter[<?= $input['id']; ?>]"
					   value="<?= isset($_POST['filter'][$input['id']])?$_POST['filter'][$input['id']]:''; ?>">
			</div>
		<?
		}
		?>
		<div class="filter">
			<h4>Переменная выборки</h4>
			<input name="lambda"
				   value="<?= isset($_POST['lambda'])?$_POST['lambda']:FRIS::LAMBDA; ?>">
		</div>
		<div class="clearfix"></div>
		<br/>
		<input type="submit" value="Искать" class="btn btn-primary"/>
		<a href="/" class="btn btn-warning">Сбросить</a>
	</form>
</div>
<div class="listAll"><?
	$class = -1;
	foreach ($phones as $item) {
		/**
		 * @var Phones $item
		 */
		if($class!=$item->classID){
			$class=$item->classID;
			?>
			<div class="clearfix"></div>
			<h4>Класс <?=$class+1;?></h4>
			<?
		}
		?>
		<div class="col-md-4 tovar">
			<strong><?= $item->name; ?></strong><br/>
			<?= $item->price; ?> руб. <br/>
			<img src="<?= $item->getPhoto(); ?>" alt="<?= $item->name; ?>" class=""/><br/>

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

	.filter select {
		width: 70%;
	}

	.filter {
		float: left;
		width: 33%;
		text-align: left;
	}

	.clearfix {
		clear: both;
	}

	.center {
		text-align: center;
	}

	.left {
		text-align: left;
	}

	.filter-form {
		margin: 0 auto;
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
