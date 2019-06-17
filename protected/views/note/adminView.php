<div class="b-popup">
	<h2 class="tc">Примечания</h2>
	<table class="b-table">
		<tr>
			<th><?=$labels["id"]?></th>
			<th><?=$labels["date"]?></th>
			<th><?=$labels["text"]?></th>
			<th><?=$labels["sum"]?></th>
			<th style="width: 100px;">Действия</th>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr>
					<td><?=$item->id?></td>
					<td><?=$item->date?></td>
					<td>
						<?=str_replace("\n", "<br>", $item->text)?>
						<? if( count($item->files) ): ?>
							<div class="b-table-docs">
								<? foreach ($item->files as $key => $file): ?>
								<div class="b-doc-file"><a href="<?=("/".Yii::app()->params['docsFolder']."/".$file->name)?>" class="b-doc" target="_blank"><?=$file->original?></a><a href="<?=Yii::app()->createUrl('/site/download',array('file_id'=>$file->id))?>" class="b-download"></a></div>
								<? endforeach; ?>
							</div>
						<? endif; ?>
					</td>
					<td><? if($item->sum): ?><?=$this->number_format( $item->sum, 2, '.', '&nbsp;' )?>&nbsp;руб.<? endif; ?></td>
					<td>
						<? if( Yii::app()->user->checkAccess('update') ): ?><a href="<?php echo Yii::app()->createUrl('/note/adminupdate',array('id'=>$item->id, "index" => true, "state_id" => $_GET["state_id"]))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать примечание"></a>
						<a href="<?=Yii::app()->createUrl('/note/admindelete',array('id'=>$item->id, "index" => true, "state_id" => $_GET["state_id"]))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить примечание"></a><? endif; ?>
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