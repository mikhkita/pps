<div class="b-section-nav">
	<div class="b-section-nav-back clearfix">
		<h1><?=$this->adminMenu["cur"]->name?></h1>
		<? if( Yii::app()->user->checkAccess('updateOrder') ): ?><a href="<?php echo $this->createUrl("/".$this->adminMenu["cur"]->code."/admincreate")?>" class="b-butt icon-add b-top-butt">Добавить заявку</a><? endif; ?>
	</div>
	<div class="b-section-actions">
		<div class="b-checkbox"><input type="checkbox" id="all-checkboxes"><label for="all-checkboxes"></label></div>
		<? if( Yii::app()->user->checkAccess('updatePayment') ): ?>
		<a href="<?php echo Yii::app()->createUrl('/payment/adminCreate',array('type'=>1))?>" class="b-icon-btn b-pay-action b-pay-action icon-card">Оплатить онлайн</a>
		<a href="<?php echo Yii::app()->createUrl('/payment/adminCreate',array('type'=>2))?>" class="b-icon-btn b-pay-action b-bill-action icon-bill">Выставить счет</a>
		<? endif; ?>
	</div>
</div>

<div class="b-section-content">
<? if(count($data)): ?>
	<? foreach ($data as $i => $order): ?>
	<div class="b-tile">
		<div class="b-tile-header clearfix">
			<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$order->id))?>"><h3 class="b-tile-title"><?=$order->getTitle()?></h3></a>
		</div>
		<table class="b-table b-overflow-table" border="0">
			<tr>
				<th class="b-checkbox"><input type="checkbox" id="order-<?=$order->id?>" class="b-table-checkbox"><label for="order-<?=$order->id?>"></label></th>
				<th style="width: 20%;"><?=$labels["fio"]?></th>
				<th style="width: 148px;"><?=$labels["phone"]?></th>
				<th><?=$labels["address"]?></th>
				<th style="width: 7%;"><?=$labels["status"]?></th>
				<th style="width: 111px;"><?=$labels["price"]?></th>
				<th style="width: 102px;"><?=$labels["cash"]?></th>
				<th style="width: 103px;"><?=$labels["commission"]?></th>
				<th style="width: 123px;"><?=$labels["payment_status"]?></th>
			</tr>
			<? foreach ($order->persons as $key => $person): ?>
			<tr>
				<td class="b-checkbox"><input type="checkbox" class="b-person-checkbox" value="<?=$person->id?>" id="person-<?=$person->id?>"><label for="person-<?=$person->id?>"></label></td>
				<td><?=$person->fio?></td>
				<td><nobr><?=$person->phone?></nobr></td>
				<td><?=$person->address?></td>
				<td><nobr><? if( $person->to_status_id ): ?><span class="<?=$person->getStatusColor($person->to_status_id)?> tooltip" title="Туда"><?=$person->to_status?></span><? if( $person->from_status_id ): ?> / <? endif ?><? endif ?><? if( $person->from_status_id ): ?><span class="<?=$person->getStatusColor($person->from_status_id)?> tooltip" title="Обратно"><?=$person->from_status?></span><? endif ?></nobr></td>
				<td class="tr icon-rub-regular"><?=number_format( $person->price, 0, ',', '&nbsp;' )?></td>
				<td class="tr icon-rub-regular"><?=number_format( $person->cash, 0, ',', '&nbsp;' )?></td>
				<td class="tr icon-rub-regular"><?=number_format( $person->commission, 0, ',', '&nbsp;' )?></td>
				<td><span class="green">Оплачено</span></td>
			</tr>
			<? endforeach; ?>
			<tr class="b-big-row">
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="b-label-block tr"><label>Итого:</label></td>
				<td class="tr"><h4 class="icon-rub-bold"><?=number_format( $order->getTotalPrice(), 0, ',', '&nbsp;' )?></h4></td>
				<td class="tr"><h4 class="icon-rub-bold"><?=number_format( $order->getTotalCash(), 0, ',', '&nbsp;' )?></h4></td>
				<td class="tr"><h4 class="icon-rub-bold"><?=number_format( $order->getTotalCommission(), 0, ',', '&nbsp;' )?></h4></td>
				<td><span class="orange">Оплачено частично</span></td>
			</tr>
		</table>
	</div>
	<? endforeach; ?>
<? else: ?>
	<p class="tc">У вас пока нет заявок</p>
<? endif; ?>
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
