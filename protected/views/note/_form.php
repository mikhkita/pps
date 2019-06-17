<div class="b-popup-form">

<?php $form=$this->beginWidget("CActiveForm", array(
	"id" => "faculties-form",
	"enableAjaxValidation" => false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
	<input type="hidden" name="Note[item_id]" value="<?=$model->item_id?>">
	<input type="hidden" name="Note[type_id]" value="<?=$model->type_id?>">

	<div class="row">
		<?php echo $form->labelEx($model, "text"); ?>
		<?php echo $form->textArea($model, "text", array("maxlength" => 4096, "required" => true)); ?>
		<?php echo $form->error($model, "text"); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, "sum"); ?>
		<?php echo $form->textField($model, "sum", array("maxlength" => 20, "class" => "float")); ?>
		<?php echo $form->error($model, "sum"); ?>
	</div>

	<? if( count($model->files) ): ?>
	<div class="row">
		<? foreach ($model->files as $key => $file): ?>
		<div class="b-doc-file"><a href="<?=("/".Yii::app()->params['docsFolder']."/".$file->id."/".$file->original)?>" class="b-doc" target="_blank"><?=$file->original?></a><a href="<?=Yii::app()->createUrl('/site/download',array('file_id'=>$file->id))?>" class="b-download"></a><a href="#" class="b-file-remove" data-id="file-<?=$file->id?>"></a></div>
		<input type="checkbox" name="Remove[]" id="file-<?=$file->id?>" value="<?=$file->id?>" style="display:none;">
		<? endforeach; ?>
	</div>
	<? endif; ?>

	<div class="row">
		<input type="hidden" name="files" id="files">
		<?php $this->renderPartial("/uploader/form", array('maxFiles'=>40,'extensions'=>'png,jpg,jpeg,JPEG,gif,pdf,xml,doc,docx,txt,xls,xlsx,csv', 'title' => 'Прикрепление файлов', 'selector' => '#files')); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? "Добавить" : "Сохранить"); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->