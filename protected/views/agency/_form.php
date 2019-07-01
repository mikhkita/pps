<div class="b-popup-form">

<?php $form=$this->beginWidget("CActiveForm", array(
	"id" => "faculties-form",
	"enableAjaxValidation" => false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row clearfix b-input">
		<?php echo $form->labelEx($model, "name"); ?>
		<?php echo $form->textField($model, "name", array("maxlength" => 64, "required" => true)); ?>
		<?php echo $form->error($model, "name"); ?>
	</div>

	<div class="row clearfix">
		<div class="row-half b-input">
			<?php echo $form->labelEx($model, "default_payment_type_id"); ?>
			<?php echo $form->dropDownList($model, "default_payment_type_id", array(0 => "Не выбрано") + $model->payments, array("class" => "select2")); ?>
			<?php echo $form->error($model, "default_payment_type_id"); ?>
		</div>
		<div class="row-half b-input">
			<?php echo $form->labelEx($model, "default_start_point_id"); ?>
			<?php echo $form->dropDownList($model, "default_start_point_id", array(0 => "Не выбрано") + CHtml::listData(Point::model()->sorted()->active()->endAvailable()->findAll(), "id", "name"), array("class" => "select2")); ?>
			<?php echo $form->error($model, "default_start_point_id"); ?>
		</div>
	</div>

	<? if( Yii::app()->user->checkAccess('root') ): ?>
	<div class="b-input">
		<?php echo $form->labelEx($model, "code_1c"); ?>
		<?php echo $form->textField($model, "code_1c", array("maxlength" => 64)); ?>
		<?php echo $form->error($model, "code_1c"); ?>
	</div>
	<? endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? "Добавить" : "Сохранить"); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->