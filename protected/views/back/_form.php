<div class="b-popup-form">

<?php $form=$this->beginWidget("CActiveForm", array(
	"id" => "faculties-form",
	"enableAjaxValidation" => false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row clearfix">
		<?php echo $form->labelEx($model, "reason"); ?>
		<?php echo $form->textArea($model, "reason", array("maxlength" => 4096, "required" => true, "rows" => 7)); ?>
		<?php echo $form->error($model, "reason"); ?>
	</div>

	<? foreach ($persons as $key => $person): ?>
		<?php echo $form->textField($person, "reason", array("maxlength" => 4096, "required" => true, "rows" => 7)); ?>
	<? endforeach; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? "Добавить" : "Сохранить"); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->