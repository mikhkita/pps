<div class="b-popup-form b-law-form">

<?php $form=$this->beginWidget("CActiveForm", array(
	"id" => "faculties-form",
	"enableAjaxValidation" => false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<input type="hidden" name="Execution[section_id]" value="<?=$_GET["section_id"]?>">

	<div style="display:none;">
		<?php echo $form->textField($model, "law_id", array("maxlength" => 10 )); ?>
	</div>

	<div class="row clearfix">
		<?php echo $form->labelEx($model, "debtor"); ?>
		<?php echo $form->textField($model, "debtor", array("maxlength" => 128, "required" => true)); ?>
		<?php echo $form->error($model, "debtor"); ?>
	</div>

	<div class="row clearfix">
		<div class="row-half line-inputs">
			<?php echo $form->labelEx($model, "is_material"); ?>
			<?php echo $form->radioButtonList($model, "is_material", array(1 => "Да", 0 => "Нет"), array("separator"=>"", "class" => "autofirst type-checkbox")); ?>
			<?php echo $form->error($model, "is_material"); ?>
		</div>
		<div class="b-type-1 b-type-item">
			<div class="row-fourth">
				<?php echo $form->labelEx($model, "debt_1"); ?>
				<?php echo $form->textField($model, "debt_1", array("maxlength" => 20, "class" => "float")); ?>
				<?php echo $form->error($model, "debt_1"); ?>
			</div>
			<div class="row-fourth">
				<?php echo $form->labelEx($model, "debt_2"); ?>
				<?php echo $form->textField($model, "debt_2", array("maxlength" => 20, "class" => "float")); ?>
				<?php echo $form->error($model, "debt_2"); ?>
			</div>
		</div>
		<div class="b-type-0 b-type-item">
			<div class="row-half">
				<?php echo $form->labelEx($model, "text"); ?>
				<?php echo $form->textField($model, "text", array("maxlength" => 1024)); ?>
				<?php echo $form->error($model, "text"); ?>
			</div>
		</div>
	</div>

	<div class="row b-type-1 b-type-item clearfix">
		<div class="row-fourth">
			<?php echo $form->labelEx($model, "debt_3"); ?>
			<?php echo $form->textField($model, "debt_3", array("maxlength" => 20, "class" => "float")); ?>
			<?php echo $form->error($model, "debt_3"); ?>
		</div>
		<div class="row-fourth">
			<?php echo $form->labelEx($model, "debt_4"); ?>
			<?php echo $form->textField($model, "debt_4", array("maxlength" => 20, "class" => "float")); ?>
			<?php echo $form->error($model, "debt_4"); ?>
		</div>
		<div class="row-fourth">
			<?php echo $form->labelEx($model, "debt_5"); ?>
			<?php echo $form->textField($model, "debt_5", array("maxlength" => 20, "class" => "float")); ?>
			<?php echo $form->error($model, "debt_5"); ?>
		</div>
		<div class="row-fourth">
			<?php echo $form->labelEx($model, "debt_6"); ?>
			<?php echo $form->textField($model, "debt_6", array("maxlength" => 20, "class" => "float")); ?>
			<?php echo $form->error($model, "debt_6"); ?>
		</div>
	</div>

	<div class="row clearfix">
		<div class="row-half">
			<?php echo $form->labelEx($model, "start_date"); ?>
			<?php echo $form->textField($model, "start_date", array("maxlength" => 20, "class" => "date", "required" => true)); ?>
			<?php echo $form->error($model, "start_date"); ?>
		</div>
		<div class="row-half">
			<?php echo $form->labelEx($model, "end_date"); ?>
			<?php echo $form->textField($model, "end_date", array("maxlength" => 20, "class" => "date")); ?>
			<?php echo $form->error($model, "end_date"); ?>
		</div>
	</div>

	<div class="row clearfix">
		<!-- <div class="row-half"> -->
			<?php echo $form->labelEx($model, "manager_id"); ?>
			<?php echo $form->dropDownList($model, "manager_id", CHtml::listData(User::model()->sorted()->with("sections")->findAll("sections.section_id = '".$model->section_id."'"), 'id', 'fio'), array("class" => "select2", "required" => true)); ?>
			<?php echo $form->error($model, "manager_id"); ?>
		<!-- </div> -->
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, "stakeholders"); ?>
		<?php echo Chtml::dropDownList("Stakeholders", $stakeholders, CHtml::listData(User::model()->sorted()->with("sections")->findAll("sections.section_id = '".$model->section_id."'"), 'id', 'fio'), array("class" => "select2", 'multiple' => 'true')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, "comment"); ?>
		<?php echo $form->textArea($model, "comment", array("maxlength" => 4096)); ?>
		<?php echo $form->error($model, "comment"); ?>
	</div>

	<div class="row line-inputs">
		<?php echo $form->labelEx($model, "state_id"); ?>
		<?php echo $form->radioButtonList($model, "state_id", CHtml::listData(State::model()->findAll(), 'id', 'name'), array("separator"=>"", "template"=>"<div class='b-inline-checkbox'>{input} {label}</div>")); ?>
		<?php echo $form->error($model, "state_id"); ?>
	</div>

	<? if( $model->is_ending ): ?>
		<div class="row checkbox-row clearfix">
			<?php echo $form->labelEx($model, "highlight"); ?>
			<?php echo $form->checkbox($model, "highlight"); ?>
			<?php echo $form->error($model, "highlight"); ?>
		</div>
	<? endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? "Добавить" : "Сохранить"); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->