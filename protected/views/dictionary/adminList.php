<a href="<?php echo $this->createUrl("/".$this->adminMenu["cur"]->code."/adminIndex")?>" class="b-back">Назад</a>
<h1><?=$this->adminMenu["items"][ $_GET["class"] ]->name?></h1>

<? if( Yii::app()->user->checkAccess('updateDictionary') ): ?><a href="<?php echo $this->createUrl("/".$this->adminMenu["cur"]->code."/adminCreate", array('class' => $_GET["class"]))?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a><? endif; ?>
<?php $form=$this->beginWidget('CActiveForm'); ?>
<table class="b-table" border="0">
	<tr>
		<? foreach ($labels as $key => $label): ?>
			<th <? if( isset($label->width) ): ?>style="width: <?=$label->width?>;"<? endif; ?>><?=$label->name?></th>
		<? endforeach; ?>
		<th style="width: 100px;">Действия</th>
	</tr>
	<tr class="b-filter">
		<? foreach ($labels as $key => $label): ?>
			<? if($key == "id"): ?>
				<td></td>
			<? continue;
			   endif; ?>
			<? switch($label->type):
				case "bool": ?>
					<td><?php echo CHtml::activeDropDownList($filter, $key, array(0 => "Нет", 1 => "Да"), array("class" => "select2", "empty" => "Не выбрано", "tabindex" => 1, "placeholder" => "Не выбрано")); ?></td>
					<?break;
				case "select": ?>
					<td><?php echo CHtml::activeDropDownList($filter, $key, CHtml::listData($label->model::model()->sorted()->findAll(), 'id', 'name'), array("class" => "select2", "empty" => "Все", "tabindex" => 1, "placeholder" => "Не выбрано")); ?></td>
					<?break;?>
				
				<? default: ?>
					<td><?php echo CHtml::activeTextField($filter, $key, array('tabindex' => 1, "placeholder" => "Поиск")); ?></td>
			<? endswitch; ?>
		<? endforeach; ?>
		<td class="tc"><a href="#" class="b-clear-filter">Сбросить</a><input type="hidden" name="class" value="<?=$_GET["class"]?>"></td>
	</tr>
	<? if(count($data)): ?>
		<? foreach ($data as $i => $item): ?>
			<tr>
				<? foreach ($labels as $key => $field): ?>
					<td <? if( isset($field->class) ): ?>class="<?=$label->class?>"<? endif; ?>>
						<? switch($field->type):
							case "bool": ?>
								<?=( ($item->{$key})?"Да":"Нет" )?>
								<?break;?>
							
							<? default: ?>
								<? if( $field->relation ): ?>
									<?=$item->{$field->relation}->name?>
								<? else: ?>
									<?=$item->{$key}?>
								<? endif; ?>
						<? endswitch; ?>
					</td>
				<? endforeach; ?>
				<td>
					<? if( Yii::app()->user->checkAccess('updateDictionary') ): ?><a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id, 'class' => $_GET["class"]))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["items"][ $_GET["class"] ]->vin_name?>"></a>
					<a href="<?=Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$item->id, 'class' => $_GET["class"]))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить <?=$this->adminMenu["items"][ $_GET["class"] ]->vin_name?>" data-name="<?=$this->adminMenu["items"][ $_GET["class"] ]->vin_name?>"></a><? endif; ?>
				</td>
			</tr>
		<? endforeach; ?>
	<? else: ?>
		<tr>
			<td colspan="20" class="tc">Ничего не найдено</td>
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
    <div class="b-lot-count">Всего элементов: <?=$count?></div>
</div>
