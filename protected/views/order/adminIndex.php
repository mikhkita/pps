<h1><?=$this->adminMenu["cur"]->name?></h1>
<? if( Yii::app()->user->checkAccess('updateSection') ): ?><a href="<?php echo $this->createUrl("/".$this->adminMenu["cur"]->code."/admincreate")?>" class="b-butt icon-add b-top-butt">Добавить заявку</a><? endif; ?>
<div class="b-tile">
	<div class="b-tile-header clearfix">
		<a href="#"><h3 class="b-tile-title">Томск – Толмачево 24 мая 2019 г. (3 чел.)</h3></a>
		<div class="b-tile-header-right">
			<div class="b-label-block">
				<label>Итого:</label>
				<h3>4 500 ₽</h3>
			</div>
		</div>
	</div>
	<table class="b-table b-overflow-table" border="0">
		<tr>
			<th></th>
			<th>ФИО</th>
			<th>Телефон</th>
			<th>Адрес</th>
			<th>Статус</th>
			<th>Стоимость</th>
			<th>Принято</th>
			<th>Комиссия</th>
			<th>Оплата</th>
		</tr>
		<tr>
			<td></td>
			<td>Китаев Михаил Андреевич</td>
			<td>+7 (999) 499-50-00</td>
			<td>г. Томск, пер. Дербышевский 26б, оф. 301, 3 этаж</td>
			<td><span class="green tooltip" title="В обе стороны">Бронь</span></td>
			<td class="tr">1 800 ₽</td>
			<td class="tr">1 800 ₽</td>
			<td class="tr">250 ₽</td>
			<td><span class="green">Оплачено</span></td>
		</tr>
		<tr>
			<td></td>
			<td>Китаева Валерия Евгеньевна</td>
			<td>+7 (999) 499-50-01</td>
			<td>г. Томск, пер. Дербышевский 26б</td>
			<td><span class="green tooltip" title="В обе стороны">Бронь</span></td>
			<td class="tr">1 800 ₽</td>
			<td class="tr">1 800 ₽</td>
			<td class="tr">250 ₽</td>
			<td><span class="orange">Оплачено частично</span></td>
		</tr>
		<tr>
			<td></td>
			<td>Китаев Лев Михайлович</td>
			<td>+7 (999) 499-50-02</td>
			<td>г. Томск, пер. Дербышевский 26б</td>
			<td><span class="red tooltip" title="Туда">Отменен</span> / <span class="green tooltip" title="Обратно">Бронь</span></td>
			<td class="tr">1 800 ₽</td>
			<td class="tr">1 800 ₽</td>
			<td class="tr">125 ₽</td>
			<td><span class="green">Оплачено обратно</span></td>
		</tr>
		<tr class="b-big-row">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td class="b-label-block tr"><label>Итого:</label></td>
			<td class="tr"><h4>4 500 ₽</h4></td>
			<td class="tr"><h4>4 500 ₽</h4></td>
			<td class="tr"><h4>625 ₽</h4></td>
			<td><span class="orange">Оплачено частично</span></td>
		</tr>
	</table>
</div>
<?php /* $form=$this->beginWidget('CActiveForm'); ?>
<table class="b-table" border="0">
	<tr>
		<th style="width: 30px;">№</th>
		<th><? echo $labels["name"]; ?></th>
		<th><? echo $labels["code_1c"]; ?></th>
		<th style="width: 100px;">Действия</th>
	</tr>
	<tr class="b-filter">
		<td></td>
		<td><?php echo CHtml::activeTextField($filter, 'name', array('tabindex' => 1, "placeholder" => "Поиск по наименованию")); ?></td>
		<td></td>
		<td><a href="#" class="b-clear-filter">Сбросить</a></td>
	</tr>
	<? if(count($data)): ?>
		<? foreach ($data as $i => $item): ?>
			<tr>
				<td><? echo $item->id; ?></td>
				<td class="align-left"><? echo $item->name; ?></td>
				<td class="align-left"><? echo $item->code_1c; ?></td>
				<td>
					<? if( Yii::app()->user->checkAccess('updateSection') ): ?><a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a>
					<a href="<?=Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a><? endif; ?>
				</td>
			</tr>
		<? endforeach; ?>
	<? else: ?>
		<tr>
			<td colspan="20">Ничего не найдено, попробуйте изменить фильтр</td>
		</tr>
	<? endif; ?>
</table>
<?php $this->endWidget(); ?>
<div class="b-pagination-cont clearfix">
    <?php $this->widget('CLinkPager', array(
        'header' => '',
        'lastPageLabel' => 'последняя &raquo;',
        'firstPageLabel' => '&laquo; первая', 
        'pages' => $pages,
        'prevPageLabel' => '< назад',
        'nextPageLabel' => 'далее >'
    )) ?>
    <div class="b-lot-count">Всего групп: <?=$count?></div>
</div>
<? */ ?>
