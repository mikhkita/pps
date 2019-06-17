<h1><?=$this->adminMenu["cur"]->name?></h1>
<?php $form=$this->beginWidget('CActiveForm'); ?>
<table class="b-table no-last" border="0">
	<tr>
		<th style="width: 209px;min-width: 209px;max-width: 209px;"><? echo $labels["date"]; ?></th>
		<th><? echo $labels["user_id"]; ?></th>
		<th><? echo $labels["action_id"]; ?></th>
		<th><? echo $labels["model_id"]; ?></th>
		<th><? echo $labels["item"]; ?></th>
		<th><? echo $labels["data"]; ?></th>
		<th></th>
	</tr>
	<tr class="b-filter">
		<td><?php echo CHtml::activeTextField($filter, 'date_from', array('tabindex' => 0, "placeholder" => "От", "class" => "date")); ?><span class="filter-separator">-</span><?php echo CHtml::activeTextField($filter, 'date_to', array('tabindex' => 0, "placeholder" => "До", "class" => "date")); ?></td>
		<td><?php echo CHtml::activeDropDownList($filter, 'user_id', CHtml::listData(User::model()->findAll(), 'id', 'fio'), array("class" => "select2", "empty" => "Все пользователи", "tabindex" => 1)); ?></td>
		<td><?php echo CHtml::activeDropDownList($filter, 'action_id', CHtml::listData(Action::model()->findAll(), 'id', 'name'), array("class" => "select2", "empty" => "Все действия", "tabindex" => 1)); ?></td>
		<td><?php echo CHtml::activeDropDownList($filter, 'model_id', CHtml::listData(ModelNames::model()->findAll(), 'id', 'name'), array("class" => "select2", "empty" => "Все разделы", "tabindex" => 1)); ?></td>
		<td><?php echo CHtml::activeTextField($filter, 'item', array('tabindex' => 2, "placeholder" => "Поиск по элементу")); ?></td>
		<td><?php echo CHtml::activeTextField($filter, 'data', array('tabindex' => 3, "placeholder" => "Поиск по примечанию")); ?></td>
		<td class="tc"><a href="#" class="b-clear-filter">Сбросить фильтр</a></td>
	</tr>
	<? if(count($data)): ?>
		<? foreach ($data as $i => $item): ?>
			<tr>
				<td><?=$item->date?></td>
				<td><?=$item->user->fio?></td>
				<td><?=$item->action->name?></td>
				<td><?=$item->model->name?></td>
				<td><?=$item->item?></td>
				<td><?=$item->data?></td>
				<td></td>
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
