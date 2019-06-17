<div class="b-popup-form">

<?php $form=$this->beginWidget("CActiveForm", array(
	"id" => "faculties-form",
	"enableAjaxValidation" => false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<input type="hidden" name="Pretension[section_id]" value="<?=$_GET["section_id"]?>">

	<? if( $section->is_dept ): ?>
		<div class="row clearfix">
			<div class="row-half">
				<?php echo $form->labelEx($model, "contractor"); ?>
				<?php echo $form->textField($model, "contractor", array("maxlength" => 64, "required" => true)); ?>
				<?php echo $form->error($model, "contractor"); ?>
			</div>
			<div class="row-half">
				<?php echo $form->labelEx($model, "dept_id"); ?>
				<?php echo $form->dropDownList($model, "dept_id", CHtml::listData(Dept::model()->findAll(), 'id', 'name'), array("class" => "select2")); ?>
				<?php echo $form->error($model, "dept_id"); ?>
			</div>
		</div>
	<? else: ?>
		<div class="row">
			<?php echo $form->labelEx($model, "contractor"); ?>
			<?php echo $form->textField($model, "contractor", array("maxlength" => 64, "required" => true)); ?>
			<?php echo $form->error($model, "contractor"); ?>
		</div>
	<? endif; ?>

	<div class="row clearfix">
		<div class="row-half">
			<?php echo $form->labelEx($model, "send_date"); ?>
			<?php echo $form->textField($model, "send_date", array("maxlength" => 20, "class" => "date", "required" => true)); ?>
			<?php echo $form->error($model, "send_date"); ?>
		</div>
		<div class="row-half">
			<?php echo $form->labelEx($model, "days"); ?>
			<?php echo $form->textField($model, "days", array("maxlength" => 5, "required" => true, "class" => "numeric")); ?>
			<?php echo $form->error($model, "days"); ?>
		</div>
	</div>

	<div class="row clearfix">
		<div class="row-half">
			<?php echo $form->labelEx($model, "debt"); ?>
			<?php echo $form->textField($model, "debt", array("maxlength" => 20, "required" => true, "class" => "float")); ?>
			<?php echo $form->error($model, "debt"); ?>
		</div>
		<div class="row-half">
			<?php echo $form->labelEx($model, "manager_id"); ?>
			<?php echo $form->dropDownList($model, "manager_id", CHtml::listData(User::model()->sorted()->with("sections")->findAll("sections.section_id = '".$model->section_id."'"), 'id', 'fio'), array("class" => "select2", "required" => true)); ?>
			<?php echo $form->error($model, "manager_id"); ?>
		</div>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, "stakeholders"); ?>
		<?php echo Chtml::dropDownList("Stakeholders", $stakeholders, CHtml::listData(User::model()->sorted()->with("sections")->findAll("sections.section_id = '".$model->section_id."'"), 'id', 'fio'), array("class" => "select2", 'multiple' => 'true')); ?>
	</div>

	<div class="row clearfix">
		<div class="row-half checkbox-row clearfix">
			<?php echo $form->labelEx($model, "archive"); ?>
			<?php echo $form->checkbox($model, "archive"); ?>
			<?php echo $form->error($model, "archive"); ?>
		</div>
		<? if( $model->is_expired ): ?>
			<div class="row-half checkbox-row clearfix">
				<?php echo $form->labelEx($model, "highlight"); ?>
				<?php echo $form->checkbox($model, "highlight"); ?>
				<?php echo $form->error($model, "highlight"); ?>
			</div>
		<? endif; ?>
	</div>

	<div class="b-convert">
		<a href="<?=Yii::app()->createUrl('/law/adminCreate',array("section_id" => $model->section_id, "archive" => $_GET["archive"], "pretension_id" => $model->id))?>" onclick="$.fancybox.close();" class="ajax-form ajax-create">Создать судебное дело</a>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? "Добавить" : "Сохранить"); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->