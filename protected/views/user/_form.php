<div class="form b-popup-form">

<?php $form=$this->beginWidget("CActiveForm", array(
	"id" => "faculties-form",
	"enableAjaxValidation" => false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row checkbox-row clearfix">
		<?php echo $form->labelEx($model, "active"); ?>
		<?php echo $form->checkbox($model, "active"); ?>
		<?php echo $form->error($model, "active"); ?>
	</div>

	<div class="row clearfix">
		<div class="row-half b-input">
			<?php echo $form->labelEx($model, "name"); ?>
			<?php echo $form->textField($model, "name", array("maxlength" => 255, "required" => true)); ?>
			<?php echo $form->error($model, "name"); ?>
		</div>
		<div class="row-half b-input">
			<?php echo $form->labelEx($model, "email"); ?>
			<?php echo $form->textField($model, "email", array("maxlength" => 255)); ?>
			<?php echo $form->error($model, "email"); ?>
		</div>
	</div>

	<div class="row clearfix">
		<div class="row-half b-input">
			<?php echo $form->labelEx($model, "login"); ?>
			<?php echo $form->textField($model, "login", array("maxlength" => 255, "required" => true)); ?>
			<?php echo $form->error($model, "login"); ?>
		</div>

		<div class="row-half b-input">
			<?php echo $form->labelEx($model, "password"); ?>
			<?php echo $form->passwordField($model, "password", array("size" => 60, "maxlength" => 128, "required" => true)); ?>
			<?php echo $form->error($model, "password"); ?>
		</div>
	</div>

	<div class="row clearfix">
		<div class="row-half b-input">
			<?php echo $form->labelEx($model, "roles"); ?>
			<?=CHTML::checkBoxList("Roles", $roles, CHtml::listData($roleList, "id", "name"), array()); ?>
			<?php echo $form->error($model, "Roles"); ?>
		</div>
		<? if( Yii::app()->user->checkAccess('accessAll') ): ?>
		<div class="row-half b-input">
			<?php echo $form->labelEx($model, "agency_id"); ?>
			<?php echo $form->dropDownList($model, "agency_id", array(0 => "Не выбрано") + CHtml::listData(Agency::model()->sorted()->findAll(), "id", "name"), array("class" => "select2")); ?>
			<?php echo $form->error($model, "agency_id"); ?>
		</div>
		<? elseif( Yii::app()->user->checkAccess('accessAgency') ): ?>
			<?php echo $form->hiddenField($model, "agency_id", array("maxlength" => 255, "required" => true)); ?>
		<? endif; ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? "Добавить" : "Сохранить"); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->