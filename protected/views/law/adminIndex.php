<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<ul class="b-section-menu clearfix left">
			<li><a href="<?=$this->createUrl('/'.$this->adminMenu['cur']->code.'/adminindex')?>"<? if($archive == 0): ?> class="active"<? endif; ?>>Активные</a></li>
			<li><a href="<?=$this->createUrl('/'.$this->adminMenu['cur']->code.'/adminindex', array('archive' => 1))?>"<? if($archive == 1): ?> class="active"<? endif; ?>>В архиве</a></li>
		</ul>
	</div>
</div>
<h1 class="b-with-nav"><?=$this->adminMenu["cur"]->name?></h1>
<? if( Yii::app()->user->checkAccess('updateAll') ): ?><? endif; ?>
<?php $form=$this->beginWidget('CActiveForm'); ?>
<table class="b-table" border="0">
	<tr>
		<th style="width: 30px;">№</th>
		<th><?=$labels["plf"]?></th>
		<th><?=$labels["dft"]?></th>
		<th><?=$labels["debt"]?></th>
		<th><?=$labels["court"]?></th>
		<th><?=$labels["number"]?></th>
		<th><?=$labels["notes"]?></th>
		<th><?=$labels["manager_id"]?></th>
		<th style="width: 130px;">Действия</th>
	</tr>
	<tr class="b-filter">
		<td><input type="hidden" name="archive" value="<?=$archive?>"></td>
		<td><?php echo CHtml::activeTextField($filter, 'plf', array('tabindex' => 1, "placeholder" => "Поиск по истцу")); ?></td>
		<td><?php echo CHtml::activeTextField($filter, 'dft', array('tabindex' => 2, "placeholder" => "Поиск по ответчику")); ?></td>
		<td></td>
		<td><?php echo CHtml::activeTextField($filter, 'court', array('tabindex' => 3, "placeholder" => "Поиск по суду")); ?></td>
		<td></td>
		<td></td>
		<td><?php echo CHtml::activeDropDownList($filter, 'manager_id', CHtml::listData(User::model()->sorted()->findAll(), 'id', 'fio'), array("class" => "select2", "empty" => "Все", "tabindex" => 1, "placeholder" => "Поиск по ответственому")); ?></td>
		<td><a href="#" class="b-clear-filter">Сбросить</a></td>
	</tr>
	<? if(count($data)): ?>
		<? foreach ($data as $i => $section): ?>
			<tr class="b-title">
				<td colspan="8" class="tl"><?=$section->name?></td>
				<td colspan="20"><? if( Yii::app()->user->checkAccess('updateAll') ): ?><a href="<?=Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminCreate',array("section_id" => $section->id, "archive" => $archive))?>" class="ajax-form ajax-create b-tool b-tool-add" title="Добавить <?=$this->adminMenu["cur"]->vin_name?> в группу"></a><? endif; ?></td>
			</tr>
			<? if(count($section->items)): ?>
				<? foreach ($section->items as $j => $item): ?>
					<tr>
						<td><a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminview',array('id' => $item->id))?>" title="Просмотр <?=$this->adminMenu["cur"]->rod_name?>" class="b-double-click"><?=$item->id?></a></td>
						<td><?=$item->plf?></td>
						<td><?=$item->dft?></td>
						<td>
							<? if( $item->is_material ): ?>
								<span class="b-tooltip" title="<?=$item->getTooltip()?>"><?=$this->number_format( $item->debt - $item->recovered, 2, '.', '&nbsp;' )?>&nbsp;руб.</span>
							<? else: ?>
								<?=$item->text?>
							<? endif; ?>
						</td>
						<td><?=$item->court?></td>
						<td><?=$item->number?></td>
						<td class="b-inline-docs">
							<? if($item->last_note): ?>
								<?=$item->last_note->date?>: <?=$item->last_note->text?> <? if($item->last_note->sum): ?>(взыскано: <?=$this->number_format( $item->last_note->sum, 2, '.', '&nbsp;' )?> руб.)<? endif; ?>
								<? if( count($item->last_note->files) ): ?>
									<? foreach ($item->last_note->files as $key => $file): ?>
									<div class="b-doc-file"><a href="<?=("/".Yii::app()->params['docsFolder']."/".$file->name)?>" class="b-doc" target="_blank"><?=$file->original?></a><a href="<?=Yii::app()->createUrl('/site/download',array('file_id'=>$file->id))?>" class="b-download"></a></div>
									<? endforeach; ?>
								<? endif; ?>
								<? if( (count($item->notes)-1) ): ?>
									<a href="<?php echo $this->createUrl("/note/adminView", array("item_id" => $item->id, "type_id" => 2))?>" class="ajax-update">и&nbsp;еще&nbsp;<?=(count($item->notes)-1)?></a>
								<? endif; ?>
							<? endif; ?>
						</td>
						<td><?=$item->manager->fio?></td>
						<td>
							<? if( Yii::app()->user->checkAccess('updateAll') ): ?>
								<a href="<?php echo $this->createUrl("/note/adminCreate", array("item_id" => $item->id, "type_id" => 2, "index" => true, "archive" => $archive))?>" class="ajax-form ajax-create b-tool b-tool-note" title="Добавить примечание"></a>
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
