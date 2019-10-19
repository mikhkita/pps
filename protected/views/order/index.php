<div class="b-section-nav">
	<div class="b-section-nav-back clearfix">
		<h1><?=$this->adminMenu["cur"]->name?></h1>
		<? if( Yii::app()->user->checkAccess('updateOrder') ): ?><a href="<?php echo $this->createUrl("/".$this->adminMenu["cur"]->code."/create")?>" class="b-butt icon-add b-top-butt">Добавить заявку</a><? endif; ?>
	</div>
	<div class="b-section-actions">
		<div class="b-checkbox"><input type="checkbox" id="all-checkboxes"><label for="all-checkboxes"></label></div>
		<? if( Yii::app()->user->checkAccess('updatePayment') ): ?>
		<a href="<?php echo Yii::app()->createUrl('/payment/create',array('type'=>1))?>" class="b-icon-btn b-pay-action icon-card">Оплатить онлайн</a>
		<a href="<?php echo Yii::app()->createUrl('/payment/create',array('type'=>2))?>" class="b-icon-btn b-pay-action icon-bill">Выставить счет</a>
		<? endif; ?>
		<a href="<?php echo Yii::app()->createUrl('/back/create')?>" class="b-icon-btn b-return-action icon-return">Оформить отмену</a>
	</div>
</div>

<div class="b-section-content">
<? if(count($data)): ?>
	<? foreach ($data as $i => $order): ?>
	<div class="b-tile">
		<div class="b-tile-header clearfix">
			<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/update',array('id'=>$order->id))?>" class="b-tile-title-link"><h3 class="b-tile-title"><?=$order->getTitle()?></h3>
			</a>
			<? if( Yii::app()->user->checkAccess('accessAgency') ): ?>
			<div class="b-tile-header-right">
				<a href="<?=Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/delete',array('id'=>$order->id))?>" data-name="<?=$this->adminMenu["cur"]->vin_name?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a>
			</div>
			<div class="b-tile-header-right">
				<h4 class="tr"><?=$order->user->fio?> <? if( Yii::app()->user->checkAccess('admin') ): ?><? endif; ?></h4>
				<p class="tr grey"><?=$order->user->agency->name?></p>
			</div>
			<? endif; ?>
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
				<th style="width: 140px;"><?=$labels["payment_status"]?></th>
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
				<td><span class="<?=$person->getPaymentStatusColor()?>"><?=$person->getPaymentStatus()?></span></td>
			</tr>
			<? endforeach; ?>
			<tr class="b-big-row">
				<td colspan="4"><p class="b-tile-header-date grey"><?=Controller::getRusDate($order->create_date)?></p></td>
				<td class="b-label-block tr"><label>Итого:</label></td>
				<td class="tr"><h4 class="icon-rub-bold"><?=number_format( $order->getTotalPrice(), 0, ',', '&nbsp;' )?></h4></td>
				<td class="tr"><h4 class="icon-rub-bold"><?=number_format( $order->getTotalCash(), 0, ',', '&nbsp;' )?></h4></td>
				<td class="tr"><h4 class="icon-rub-bold"><?=number_format( $order->getTotalCommission(), 0, ',', '&nbsp;' )?></h4></td>
				<td><span class="<?=$order->getPaymentStatusColor()?>"><?=$order->getPaymentStatus()?></span></td>
			</tr>
		</table>
	</div>
	<? endforeach; ?>
<? else: ?>
	<p class="tc">У вас пока нет заявок</p>
<? endif; ?>
</div>
<div class="b-pagination-cont clearfix">
    <?php $this->widget('CLinkPager', array(
        'header' => '',
        'lastPageLabel' => 'последняя &raquo;',
        'firstPageLabel' => '&laquo; первая', 
        'pages' => $pages,
        'prevPageLabel' => '< назад',
        'nextPageLabel' => 'далее >'
    )) ?>
    <div class="b-lot-count">Всего заявок: <?=$count?></div>
</div>