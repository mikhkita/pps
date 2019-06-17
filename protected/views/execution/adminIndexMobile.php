<h1><?=$this->adminMenu["cur"]->name?></h1>
<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<ul class="b-section-menu clearfix left">
			<? foreach ($states as $key => $state): ?>
				<li><a href="<?=$this->createUrl('/'.$this->adminMenu['cur']->code.'/adminindex', array('state_id' => $state->id))?>"<? if($state->id == $_GET["state_id"]): ?> class="active"<? endif; ?>><?=$state->section_name?></a></li>
			<? endforeach; ?>
		</ul>
	</div>
</div>
<table class="b-table" border="0">
	<tr>
		<th><?=$labels["debtor"]?></th>
		<th style="width: 90px;">Действия</th>
	</tr>
	<tr class="b-brown">
		<td colspan=3>
			<a href="#b-filter-popup" class="fancy">Фильтр</a>
		</td>
	</tr>
	<? if(count($data)): ?>
		<? foreach ($data as $i => $section): ?>
			<tr class="b-title">
				<td colspan="1" class="tl"><?=$section->name?></td>
				<td colspan="1"><? if( Yii::app()->user->checkAccess('updateAll') ): ?><a href="<?=Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminCreate',array("section_id" => $section->id, "state_id" => $_GET["state_id"]))?>" class="ajax-form ajax-create b-tool b-tool-add" title="Добавить <?=$this->adminMenu["cur"]->vin_name?> в группу"></a><? endif; ?></td>
			</tr>
			<? if(count($section->items)): ?>
				<? foreach ($section->items as $j => $item): ?>
					<tr <?if($item->is_ending && $item->highlight):?>class="red"<? endif; ?>>
						<td>
							<?=$item->id?>
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminview',array('id' => $item->id, "state_id" => $_GET["state_id"]))?>" title="Просмотр <?=$this->adminMenu["cur"]->rod_name?>" class="b-double-click">
								<?=$item->debtor?>
							</a>
							<small class="grey block">
								<? if( $item->is_material ): ?>
									Остаток долга: <?=$this->number_format( $item->debt - $item->recovered, 2, '.', '&nbsp;' )?>&nbsp;руб.
								<? else: ?>
									Долг: <?=$item->text?>
								<? endif; ?>
							</small>
							<small class="grey block">
								с <?=$item->start_date?><? if($item->end_date): ?> по <?=$item->end_date?><? endif; ?>
							</small>
						</td>
						<td>
							<? if( Yii::app()->user->checkAccess('updateAll') ): ?>
								<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id, "state_id" => $_GET["state_id"]))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a>
								<a href="<?=Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$item->id, "state_id" => $_GET["state_id"]))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a>
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
			<input type="hidden" name="state_id" value="<?=$_GET["state_id"]?>">
			<div class="row">
				<label for=""><?=$labels["debtor"]?></label>
				<?php echo CHtml::activeTextField($filter, 'debtor', array('tabindex' => 1, "placeholder" => "Поиск по должнику")); ?>
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
