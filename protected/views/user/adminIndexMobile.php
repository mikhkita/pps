<h1>Пользователи</h1>
<a href="<?=$this->createUrl("/user/adminCreate")?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a>
<table class="b-table" border="0">
	<tr>
		<th><?=$labels["login"]?></th>
		<th><?=$labels["fio"]?></th>
		<th style="width: 90px;">Действия</th>
	</tr>
	<? foreach ($data as $i => $item): ?>
		<tr>
			<td class="align-left"><?=$item->login?></td>
			<td class="align-left"><?=$item->fio?></td>
			<td>
				<a href="<?=Yii::app()->createUrl("/user/adminUpdate",array("id"=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать раздел"></a>
				<a href="<?=Yii::app()->createUrl("/user/adminDelete",array("id"=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить раздел"></a>
			</td>
		</tr>
	<? endforeach; ?>
</table>
