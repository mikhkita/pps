<div class="b-popup-form">

<?php $form=$this->beginWidget("CActiveForm", array(
	"id" => "faculties-form",
	"enableAjaxValidation" => false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row clearfix">
		<div class="row-half">
			<?php echo $form->labelEx($model, "name"); ?>
			<?php echo $form->textField($model, "name", array("maxlength" => 64, "required" => true)); ?>
			<?php echo $form->error($model, "name"); ?>
		</div>
		<div class="row-half">
			<?php echo $form->labelEx($model, "sort"); ?>
			<?php echo $form->textField($model, "sort", array("maxlength" => 6, "required" => true, "class" => "numeric")); ?>
			<?php echo $form->error($model, "sort"); ?>
		</div>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? "Добавить" : "Сохранить"); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->