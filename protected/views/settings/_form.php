<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'faculties-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('maxlength'=>255,'required'=>true,'disabled'=> !( $this->getUserRole() == "root" ) )); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
<? if( $this->getUserRole() == "root" ):  ?>
	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('maxlength'=>255,'required'=>true)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>
<? endif; ?>
	<div class="row">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textArea($model,'value',array('class'=>"b-settings-textarea")); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->