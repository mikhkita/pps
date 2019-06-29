<h1><?=$this->adminMenu["cur"]->name?></h1>
<table class="b-table" border="0">
	<tr>
		<th><?=$labels["date"]?></th>
		<th><?=$labels["number"]?></th>
		<th><?=$labels["persons"]?></th>
		<th><?=$labels["sum"]?></th>
		<th><?=$labels["status_id"]?></th>
		<th style="width: 90px;">Действия</th>
	</tr>
	<? foreach ($data as $i => $item): ?>
		<? 
		$statusClass = "blue";

		if( $item->status_id == 2 || $item->status_id == 3 ){
			$statusClass = "orange";
		}else if( $item->status_id == 4 || $item->status_id == 5 ){
			$statusClass = "green";
		}
		?>
		<tr>
			<td><?=Controller::getRusDate($item->date)?></td>
			<td><?=$item->number?></td>
			<td><?=$item->getPersonsText()?></td>
			<td class="tr"><?=$item->getTotalSum()?></td>
			<td><span class="<?=$statusClass?>"><?=$item->status?></span></td>
			<td><? if( empty($item->number) ): ?><a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id))?>" class="b-tool b-double-click b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a><a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminDelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a><? endif; ?></td>
		</tr>
	<? endforeach; ?>
</table>
