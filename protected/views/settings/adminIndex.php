<h1><?=$this->adminMenu["cur"]->name?></h1>
<? if( 1 ):  ?>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admincreate')?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a>
<? endif; ?>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<tr>
			<th><? echo $labels['name']; ?></th>
			<? if( 1 ):  ?>
			<th><? echo $labels['code']; ?></th>
			<? endif; ?>
			<th><? echo $labels['value']; ?></th>
			<th style="width: 150px;">Действия</th>
		</tr>
		<tr class="b-filter">
			<td><?php echo CHtml::activeTextField($filter, 'name'); ?></td>
			<? if( 1 ):  ?>
			<td><?php echo CHtml::activeTextField($filter, 'code'); ?></td>
			<? endif; ?>
			<td><?php echo CHtml::activeTextField($filter, 'value'); ?></td>
			<td><a href="#" class="b-clear-filter">Сбросить фильтр</a></td>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr>
					<td class="align-left"><?=$item->name?></td>
					<? if( 1 ):  ?>
					<td class="align-left"><?=$item->code?></td>
					<? endif; ?>
					<td class="align-left"><?=$item->value?></td>
					<td class="b-tool-cont">
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a>
						<? if( 1 ):  ?>
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a>
						<? endif; ?>
					</td>
				</tr>
			<? endforeach; ?>
		<? else: ?>
			<tr>
				<td colspan=10>Пусто</td>
			</tr>
		<? endif; ?>
	</table>
<?php $this->endWidget(); ?>