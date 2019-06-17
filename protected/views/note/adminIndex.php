<h1><?=$this->adminMenu["cur"]->name?> <? if($company): ?><?=$company->name?><? endif; ?></h1>
<? if( Yii::app()->user->checkAccess('update') ): ?><a href="<?php echo $this->createUrl("/".$this->adminMenu["cur"]->code."/adminCreate")?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a><? endif; ?>
<?php $form=$this->beginWidget('CActiveForm'); ?>
<table class="b-table b-property-table" border="0">
	<tr>
		<th><? echo $labels["id"]; ?></th>
		<th><? echo $labels["name"]; ?></th>
		<th><? echo $labels["company_id"]; ?></th>
		<th><? echo $labels["square"]; ?></th>
		<th><? echo $labels["number"]; ?></th>
		<th><? echo $labels["cost"]; ?></th>
		<th><? echo $labels["cad_cost"]; ?></th>
		<th><? echo $labels["burden"]; ?></th>
		<th><? echo $labels["burden_date"]; ?></th>
		<th><? echo $labels["whose"]; ?></th>
		<th><? echo $labels["tech_status"]; ?></th>
		<th style="width: 100px;">Действия</th>
	</tr>
	<tr class="b-filter">
		<td></td>
		<td><?php echo CHtml::activeTextField($filter, 'name', array('tabindex' => 2, "placeholder" => "Поиск по наименованию/адресу")); ?></td>
		<td><?php echo CHtml::activeDropDownList($filter, 'company_id', CHtml::listData(Company::model()->findAll(), 'id', 'name'), array("class" => "select2", "empty" => "Все юр. лица", "tabindex" => 1, "placeholder" => "Поиск по юр. лицу")); ?></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td><a href="#" class="b-clear-filter">Сбросить</a></td>
	</tr>
	<? $prevCompany = 0; $prevBusiness = 0; ?>
	<? foreach ($data as $i => $item): ?>
		<? if( $prevBusiness != $item->company->business_id ): ?>
		<tr class="b-title">
			<td colspan="20" class="tl"><? echo $item->company->business->name; ?></td>
			<? $prevBusiness = $item->company->business_id; ?>
		</tr>
		<? endif; ?>
		<tr data-id="<?=$item->company_id?>" <? if($item->isEnding()): ?>class="red"<? endif; ?>>
			<td class="tc"><? echo $item->id; ?></td>
			<td><a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminview',array('id' => $item->id))?>" title="Просмотр объекта"><? echo $item->name; ?></a></td>
			<? if( $prevCompany != $item->company_id ): ?>
				<td rowspan="<?=$counts[$item->company_id]?>" id="company-<?=$item->company_id?>"><a href="<?php echo Yii::app()->createUrl('/company/adminview',array('id' => $item->company_id))?>" class="ajax-form ajax-update<? if( $item->company->isEnding() ): ?> red<? endif; ?>" title="Просмотр юр. лица"><? echo $item->company->name; ?></a></td>
				<? $prevCompany = $item->company_id; ?>
			<? endif; ?>
			<td><? echo $item->square; ?></td>
			<td><? echo $item->number; ?></td>
			<td><?=$this->number_format( $item->cost, 2, '.', '&nbsp;' )?></td>
			<td><?=$this->number_format( $item->cad_cost, 2, '.', '&nbsp;' )?></td>
			<td><? echo $item->burden; ?></td>
			<td><? if($item->isEnding()): ?><b class="red"><? echo $item->burden_date; ?></b><? else: ?><? echo $item->burden_date; ?><? endif; ?></td>
			<td><? echo $item->whose; ?></td>
			<td><? echo $item->tech_status; ?></td>
			<td>
				<? if( Yii::app()->user->checkAccess('update') ): ?><a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a>
				<a href="<?=Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a><? endif; ?>
			</td>
		</tr>
	<? endforeach; ?>
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
    <div class="b-lot-count">Всего объектов: <?=$count?></div>
</div>
