<h1>Пользователи</h1>
<a href="<?=$this->createUrl("/user/create")?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a>
<?php $form=$this->beginWidget('CActiveForm'); ?>
<table class="b-table" border="0">
	<tr>
		<th style="width: 30px;">№</th>
		<th><?=$labels["name"]?></th>
		<th><?=$labels["login"]?></th>
		<th><?=$labels["email"]?></th>
		<th><?=$labels["roles"]?></th>
		<th><?=$labels["agency_id"]?></th>
		<th style="width: 90px;">Действия</th>
	</tr>
	<tr class="b-filter">
		<td></td>
		<td></td>
		<td><?php echo CHtml::activeTextField($filter, 'login', array('tabindex' => 1, "placeholder" => "Поиск по логину")); ?></td>
		<td><?php echo CHtml::activeTextField($filter, 'email', array('tabindex' => 2, "placeholder" => "Поиск по email")); ?></td>
		<td></td>
		<td><? if( Yii::app()->user->checkAccess('accessAll') ): ?><?php echo CHtml::activeDropDownList($filter, 'agency_id', CHtml::listData(Agency::model()->sorted()->findAll(), 'id', 'name'), array("class" => "select2", "empty" => "Все агентства", "tabindex" => 1, "placeholder" => "Поиск по породе")); ?><? endif; ?></td>
		<td class="tc"><a href="#" class="b-clear-filter">Сбросить</a></td>
	</tr>
	<? if(count($data)): ?>
		<? foreach ($data as $i => $item): ?>
		<tr>
			<td><?=$item->id?></td>
			<td class="align-left"><?=$item->fio?></td>
			<td class="align-left"><?=$item->login?></td>
			<td class="align-left"><?=$item->email?></td>
			<td><?=implode(", ", $item->getRoleNames())?></td>
			<td><?=$item->agency->name?></td>
			<td><a href="<?=Yii::app()->createUrl("/user/update",array("id"=>$item->id))?>" class="ajax-form b-double-click-click ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a><a href="<?=Yii::app()->createUrl("/user/delete",array("id"=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>" data-name="<?=$this->adminMenu["cur"]->vin_name?>"></a></td>
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
    <div class="b-lot-count">Всего пользователей: <?=$count?></div>
</div>