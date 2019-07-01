<h1><?=$this->adminMenu["cur"]->name?></h1>
<? if( Yii::app()->user->checkAccess('root') ): ?><a href="<?php echo $this->createUrl("/".$this->adminMenu["cur"]->code."/adminCreate")?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a><? endif; ?>
<?php $form=$this->beginWidget('CActiveForm'); ?>
<table class="b-table" border="0">
	<tr>
		<th style="width: 30px;">№</th>
		<th><? echo $labels["name"]; ?></th>
		<th><? echo $labels["code_1c"]; ?></th>
		<th><? echo $labels["default_start_point_id"]; ?></th>
		<th><? echo $labels["default_payment_type_id"]; ?></th>
		<th style="width: 100px;">Действия</th>
	</tr>
	<tr class="b-filter">
		<td></td>
		<td><?php echo CHtml::activeTextField($filter, 'name', array('tabindex' => 1, "placeholder" => "Поиск по наименованию")); ?></td>
		<td></td>
		<td></td>
		<td></td>
		<td class="tc"><a href="#" class="b-clear-filter">Сбросить</a></td>
	</tr>
	<? if(count($data)): ?>
		<? foreach ($data as $i => $item): ?>
			<tr>
				<td><? echo $item->id; ?></td>
				<td class="align-left"><? echo $item->name; ?></td>
				<td class="align-left"><? echo $item->code_1c; ?></td>
				<td class="align-left"><? echo $item->defaultStartPoint->name; ?></td>
				<td class="align-left"><? echo $item->defaultPaymentType; ?></td>
				<td>
					<? if( Yii::app()->user->checkAccess('updateDictionary') ): ?><a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id))?>" class="ajax-form b-double-click-click ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a>
					<? if( Yii::app()->user->checkAccess('root') ): ?><a href="<?=Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$item->id))?>" data-name="<?=$this->adminMenu["cur"]->vin_name?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a><? endif; ?><? endif; ?>
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
    <div class="b-lot-count">Всего агентств: <?=$count?></div>
</div>
