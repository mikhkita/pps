<div class="clearfix">
	<h1>Испол. производство «<?=$model->debtor?>»</h1>
	<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex', array("state_id" => $model->state_id))?>" class="b-link-back b-btn">Назад</a>
</div>
<div class="clearfix b-two-cols">
	<div class="b-col">
		<table class="b-table">
			<tr>
				<th>Наименование</th>
				<th>Значение</th>
			</tr>
			<tr>
				<td width="40%"><b><?=$labels["debtor"]?>:</b></td>
				<td class="tl"><?=$model->debtor?></td>
			</tr>
			<tr>
				<td><b><?=$labels["start_date"]?>:</b></td>
				<td class="tl"><?=$model->start_date?></td>
			</tr>
			<tr <?if($model->is_ending && $model->highlight):?>class="red"<? endif; ?>>
				<td><b><?=$labels["end_date"]?>:</b></td>
				<td class="tl"><?=$model->end_date?></td>
			</tr>
			<tr>
				<td><b><?=$labels["manager_id"]?>:</b></td>
				<td class="tl"><?=$model->manager->fio?></td>
			</tr>
			<tr>
				<td><b><?=$labels["stakeholders"]?>:</b></td>
				<td class="tl"><?=$model->getStakeholdersString()?></td>
			</tr>
			<? if( $model->law_id ): ?>
				<tr>
					<td><b><?=$labels["law_id"]?>:</b></td>
					<td class="tl"><a href="<?php echo Yii::app()->createUrl('/law/adminview',array('id' => $model->law_id))?>"><?php echo Yii::app()->createUrl('/law/adminview',array('id' => $model->law_id))?></a></td>
				</tr>
			<? endif; ?>
			<? if( $model->law_id && $model->law->pretension_id ): ?>
				<tr>
					<td><b><?=$labels["pretension_id"]?>:</b></td>
					<td class="tl"><a href="<?php echo Yii::app()->createUrl('/pretension/adminview',array('id' => $model->law->pretension_id))?>"><?php echo Yii::app()->createUrl('/pretension/adminview',array('id' => $model->law->pretension_id))?></a></td>
				</tr>
			<? endif; ?>
			<? if( $model->comment ): ?>
				<tr>
					<td><b><?=$labels["comment"]?>:</b></td>
					<td class="tl"><?=$model->comment?></td>
				</tr>
			<? endif; ?>
			<tr class="b-title">
				<td colspan="7">Задолженность</td>
			</tr>
			<? if( $model->is_material ): ?>
				<tr>
					<td><?=$labels["debt_1"]?>:</td>
					<td class="tl"><?=$this->number_format( $model->debt_1, 2, '.', '&nbsp;' )?>&nbsp;руб.</td>
				</tr>
				<tr>
					<td><?=$labels["debt_2"]?>:</td>
					<td class="tl"><?=$this->number_format( $model->debt_2, 2, '.', '&nbsp;' )?>&nbsp;руб.</td>
				</tr>
				<tr>
					<td><?=$labels["debt_3"]?>:</td>
					<td class="tl"><?=$this->number_format( $model->debt_3, 2, '.', '&nbsp;' )?>&nbsp;руб.</td>
				</tr>
				<tr>
					<td><?=$labels["debt_4"]?>:</td>
					<td class="tl"><?=$this->number_format( $model->debt_4, 2, '.', '&nbsp;' )?>&nbsp;руб.</td>
				</tr>
				<tr>
					<td><?=$labels["debt_5"]?>:</td>
					<td class="tl"><?=$this->number_format( $model->debt_5, 2, '.', '&nbsp;' )?>&nbsp;руб.</td>
				</tr>
				<tr>
					<td><?=$labels["debt_6"]?>:</td>
					<td class="tl"><?=$this->number_format( $model->debt_6, 2, '.', '&nbsp;' )?>&nbsp;руб.</td>
				</tr>
				<tr>
					<td><b>Общая задолженность:</b></td>
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
			<? else: ?>
				<tr>
					<td><b><?=$labels["text"]?>:</b></td>
					<td class="tl"><?=$model->text?></td>
				</tr>
			<? endif; ?>
		</table>
	</div>
	<div class="b-col">
		<br>
		<h2 class="tc">Примечания</h2>
		<? if( Yii::app()->user->checkAccess('updateAll') ): ?><a href="<?php echo $this->createUrl("/note/adminCreate", array("item_id" => $model->id, "type_id" => 3))?>" class="ajax-form ajax-create b-butt b-top-butt inline">Добавить</a><? endif; ?>
		<table class="b-table">
			<tr>
				<th><?=$noteLabels["text"]?></th>
				<th style="width: 100px;">Действия</th>
			</tr>
			<? if( count($model->notes) ): ?>
				<? foreach ($model->notes as $i => $item): ?>
					<tr>
						<td>
							<?=$item->text?>
							<? if( count($item->files) ): ?>
								<div class="b-table-docs">
									<? foreach ($item->files as $key => $file): ?>
									<div class="b-doc-file"><a href="<?=("/".Yii::app()->params['docsFolder']."/".$file->id."/".$file->original)?>" class="b-doc" target="_blank"><?=$file->original?></a><a href="<?=Yii::app()->createUrl('/site/download',array('file_id'=>$file->id))?>" class="b-download"></a></div>
									<? endforeach; ?>
								</div>
							<? endif; ?>
							<small class="grey block"><?=$item->date?></small>
						</td>
						<td>
							<? if( Yii::app()->user->checkAccess('updateAll') ): ?><a href="<?php echo Yii::app()->createUrl('/note/adminupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать"></a>
							<a href="<?=Yii::app()->createUrl('/note/admindelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить"></a><? endif; ?>
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