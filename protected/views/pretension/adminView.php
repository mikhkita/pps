<div class="clearfix">
	<h1>Претензия «<?=$model->contractor?>»</h1>
	<? if(isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"]): ?>
		<a href="<?=$_SERVER["HTTP_REFERER"]?>" class="b-link-back b-btn">Назад</a>
	<? else: ?>
		<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex')?>" class="b-link-back b-btn">Назад</a>
	<? endif; ?>
</div>
<div class="clearfix b-two-cols">
	<div class="b-col">
		<table class="b-table">
			<tr>
				<th>Наименование</th>
				<th>Значение</th>
			</tr>
			<tr>
				<td width="40%"><b><?=$labels["contractor"]?>:</b></td>
				<td class="tl"><?=$model->contractor?></td>
			</tr>
			<tr>
				<td><b><?=$labels["send_date"]?>:</b></td>
				<td class="tl"><?=$this->getRusDate($model->send_date)?></td>
			</tr>
			<tr <?if($model->is_expired && $model->highlight):?>class="red"<? endif; ?>>
				<td><b><?=$labels["days"]?>:</b></td>
				<td class="tl"><?=($model->days." ".$this->pluralForm($model->days, array("день", "дня", "дней")))?> (<?=$this->getRusDate($model->end_date)?>)</td>
			</tr>
			<tr>
				<td><b><?=$labels["manager_id"]?>:</b></td>
				<td class="tl"><?=$model->manager->fio?></td>
			</tr>
			<tr>
				<td><b><?=$labels["stakeholders"]?>:</b></td>
				<td class="tl"><?=$model->getStakeholdersString()?></td>
			</tr>
			<tr class="b-title">
				<td colspan="7">Задолженность</td>
			</tr>
			<tr>
				<td><b><?=$labels["init_debt"]?>:</b></td>
				<td class="tl"><?=$this->number_format( $model->debt, 2, '.', '&nbsp;' )?>&nbsp;руб.</td>
			</tr>
			<tr>
				<td><b><?=$labels["recovered"]?>:</b></td>
				<td class="tl"><?=$this->number_format( $model->recovered, 2, '.', '&nbsp;' )?>&nbsp;руб.</td>
			</tr>
			<tr>
				<td><b><?=$labels["debt"]?>:</b></td>
				<td class="tl"><b><?=$this->number_format( $model->debt - $model->recovered, 2, '.', '&nbsp;' )?>&nbsp;руб.</b></td>
			</tr>
		</table>
	</div>
	<div class="b-col">
		<br>
		<h2 class="tc">Примечания</h2>
		<? if( Yii::app()->user->checkAccess('updateAll') ): ?><a href="<?php echo $this->createUrl("/note/adminCreate", array("item_id" => $model->id, "type_id" => 1))?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a><? endif; ?>
		<table class="b-table">
			<tr>
				<th><?=$noteLabels["id"]?></th>
				<th><?=$noteLabels["date"]?></th>
				<th><?=$noteLabels["text"]?></th>
				<th><?=$noteLabels["sum"]?></th>
				<th style="width: 100px;">Действия</th>
			</tr>
			<? if( count($model->notes) ): ?>
				<? foreach ($model->notes as $i => $item): ?>
					<tr>
						<td><?=$item->id?></td>
						<td><?=$item->date?></td>
						<td>
							<?=str_replace("\n", "<br>", $item->text)?>
							<? if( count($item->files) ): ?>
								<div class="b-table-docs">
									<? foreach ($item->files as $key => $file): ?>
									<div class="b-doc-file"><a href="<?=("/".Yii::app()->params['docsFolder']."/".$file->id."/".$file->original)?>" class="b-doc" target="_blank"><?=$file->original?></a><a href="<?=Yii::app()->createUrl('/site/download',array('file_id'=>$file->id))?>" class="b-download"></a></div>
									<? endforeach; ?>
								</div>
							<? endif; ?>
						</td>
						<td><? if($item->sum): ?><?=$this->number_format( $item->sum, 2, '.', '&nbsp;' )?>&nbsp;руб.<? endif; ?></td>
						<td>
							<? if( Yii::app()->user->checkAccess('updateAll') ): ?><a href="<?php echo Yii::app()->createUrl('/note/adminupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать примечание"></a>
							<a href="<?=Yii::app()->createUrl('/note/admindelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить примечание"></a><? endif; ?>
						</td>
					</tr>
				<? endforeach; ?>
			<? else: ?>
				<tr>
					<td colspan="10">Нет примечаний</td>
				</tr>
			<? endif; ?>
		</table>
	</div>
</div>