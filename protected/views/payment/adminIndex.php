<div class="b-section-nav">
	<div class="b-section-nav-back clearfix">
		<h1><?=$this->adminMenu["cur"]->name?></h1>
	</div>
</div>
<table class="b-table b-section-content" border="0">
	<tr>
		<th><?=$labels["date"]?></th>
		<th><?=$labels["type_id"]?></th>
		<th><?=$labels["number"]?></th>
		<th><?=$labels["persons"]?></th>
		<th><?=$labels["sum"]?></th>
		<th><?=$labels["status_id"]?></th>
		<th style="width: 90px;">Действия</th>
	</tr>
	<? if(count($data)): ?>
		<? foreach ($data as $i => $item): ?>
			<tr>
				<td><?=Controller::getRusDate($item->date, true)?></td>
				<td><?=$item->type->name?></td>
				<td><? if( $item->filename ): ?><a href="/<?=Yii::app()->params["fileFolder"]?>/<?=$item->filename?>" class="b-file icon-<?=$item->ext?>" target="_blank"><? endif ?><?=$item->getTitle(true)?><? if( $item->filename ): ?></a><? endif ?></td>
				<td><?=$item->getPersonsText()?></td>
				<td class="tr"><?=number_format( $item->getTotalSum(), 0, ',', '&nbsp;' )?> ₽</td>
				<td><span class="<?=$item->getStatusColor()?>"><?=$item->status?></span></td>
				<td><a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id))?>" class="b-tool b-double-click b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a><? if( $item->isEditable() ): ?><a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminDelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" data-name="<?=$this->adminMenu["cur"]->vin_name?>" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a><? endif; ?></td>
			</tr>
		<? endforeach; ?>
	<? else: ?>
		<tr>
			<td colspan="20" class="tc">Платежей не найдено</td>
		</tr>
	<? endif; ?>
</table>
<div class="b-pagination-cont clearfix">
    <?php $this->widget('CLinkPager', array(
        'header' => '',
        'lastPageLabel' => 'последняя &raquo;',
        'firstPageLabel' => '&laquo; первая', 
        'pages' => $pages,
        'prevPageLabel' => '< назад',
        'nextPageLabel' => 'далее >'
    )) ?>
    <div class="b-lot-count">Всего платежей: <?=$count?></div>
</div>