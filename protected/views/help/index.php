<h1>Инструкция пользователя</h1>
<div class="b-help">
	<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/order')?>" class="b-double-click b-tile">
		<div class="b-tile-header clearfix">
			<h3 class="b-tile-title">Заявки</h3>
			<p>Создание и просмотр заявок, добавление пассажиров, обязательные для заполнения поля, указание рейсов</p>
		</div>
	</a>

	<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/payment')?>" class="b-double-click b-tile">
		<div class="b-tile-header clearfix">
			<h3 class="b-tile-title">Платежи</h3>
			<p>Создание платежей и оплата</p>
		</div>
	</a>
</div>
