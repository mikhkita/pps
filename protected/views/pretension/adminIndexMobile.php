<h1><?=$this->adminMenu["cur"]->name?></h1>
<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<ul class="b-section-menu clearfix left">
			<li><a href="<?=$this->createUrl('/'.$this->adminMenu['cur']->code.'/adminindex')?>"<? if($archive == 0): ?> class="active"<? endif; ?>>Активные</a></li>
			<li><a href="<?=$this->createUrl('/'.$this->adminMenu['cur']->code.'/adminindex', array('archive' => 1))?>"<? if($archive == 1): ?> class="active"<? endif; ?>>В архиве</a></li>
		</ul>
	</div>
</div>
<? if( Yii::app()->user->checkAccess('updateAll') ): ?><? endif; ?>
<table class="b-table" border="0">
	<tr>
		<th>Контрагент</th>
		<th style="width: 90px;">Действия</th>
	</tr>
	<tr class="b-brown">
		<td colspan=10>
			<a href="#b-filter-popup" class="fancy">Фильтр</a>
		</td>
	</tr>
	<? if(count($data)): ?>
		<? foreach ($data as $i => $section): ?>
			<tr class="b-title">
				<td class="tl"><?=$section->name?></td>
				<td><? if( Yii::app()->user->checkAccess('updateAll') ): ?><a href="<?=Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminCreate',array("section_id" => $section->id, "archive" => $archive))?>" class="ajax-form ajax-create b-tool b-tool-add" title="Добавить <?=$this->adminMenu["cur"]->vin_name?> в группу"></a><? endif; ?></td>
			</tr>
			<? if(count($section->items)): ?>
				<? foreach ($section->items as $j => $item): ?>
					<tr <?if($item->is_expired && $item->highlight):?>class="red"<? endif; ?>>
						<td>
							<b><?=$item->id?></b> <a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminview',array('id' => $item->id))?>" title="Просмотр <?=$this->adminMenu["cur"]->rod_name?>" class="b-double-click"><?=$item->contractor?></a>
							<small class="grey block"><?=$this->number_format( $item->debt - $item->recovered, 2, '.', '&nbsp;' )?> руб. до <?=$this->getRusDate($item->end_date)?></small>
						</td>
						<td>
							<? if( Yii::app()->user->checkAccess('updateAll') ): ?>
								<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id, "archive" => $archive))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a>
								<a href="<?=Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$item->id, "archive" => $archive))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a>
							<? endif; ?>
						</td>
					</tr>
				<? endforeach; ?>
			<? else: ?>
				<tr>
					<td colspan="20">В группе нет претензий</td>
				</tr>
			<? endif; ?>
		<? endforeach; ?>
	<? else: ?>
		<tr>
			<td colspan="20">Ничего не найдено, попробуйте изменить фильтр</td>
		</tr>
	<? endif; ?>
</table>
<div style="display: none;">
	<div class="b-popup b-filter b-filter-popup" id="b-filter-popup">
		<?php $form=$this->beginWidget('CActiveForm'); ?>
			<h2>Фильтр</h2>
			<input type="hidden" name="archive" value="<?=$archive?>">
			<div class="row">
				<label for=""><?=$labels["contractor"]?></label>
				<?php echo CHtml::activeTextField($filter, 'contractor', array('tabindex' => 1, "placeholder" => "Поиск по контрагенту")); ?>
			</div>
			<div class="row">
				<label for=""><?=$labels["manager_id"]?></label>
				<?php echo CHtml::activeDropDownList($filter, 'manager_id', CHtml::listData(User::model()->sorted()->findAll(), 'id', 'fio'), array("class" => "select2", "empty" => "Все", "tabindex" => 1, "placeholder" => "Поиск по ответственому")); ?>
			</div>
			<div class="buttons">
				<input type="button" class="b-filter-submit" value="Применить">
				<a href="#" class="b-clear-filter">Сбросить фильтр</a>
			</div>
		<?php $this->endWidget(); ?>
	</div>
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
    <div class="b-lot-count">Всего групп: <?=$count?></div>
</div>
