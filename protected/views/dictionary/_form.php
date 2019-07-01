<div class="b-popup-form">

<?php $form=$this->beginWidget("CActiveForm", array(
	"id" => "faculties-form",
	"enableAjaxValidation" => false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<? 
	$required = Controller::getRequired($model);
	$maxlength = Controller::getMaxLength($model);
	foreach ($fields as $code => $field): ?>
		<? switch($field->type):
			case "bool": ?>
				<div class="row checkbox-row clearfix">
					<?php echo $form->labelEx($model, $code); ?>
					<?php echo $form->checkbox($model, $code); ?>
					<?php echo $form->error($model, $code); ?>
				</div>
				<?break;
			case "select": ?>
				<div class="row checkbox-row clearfix b-input">
					<?php echo $form->labelEx($model, $code); ?>
					<?php echo $form->dropDownList($model, $code, array(0 => "Не выбрано") + CHtml::listData($field->model::model()->sorted()->findAll(), "id", "name"), array("class" => "select2")); ?>
					<?php echo $form->error($model, $code); ?>
				</div>
				<?break;?>
			
			<? default: ?>
				<? if( Yii::app()->user->checkAccess('root') || $code != "code_1c" ): ?>
				<div class="row clearfix b-input">
					<?php echo $form->labelEx($model, $code); ?>
					<?php echo $form->textField($model, $code, array("maxlength" => ( (in_array($code, $maxlength))?$maxlength[$code]:4096 ), "required" => in_array($code, $required), "class" => $field->class )); ?>
					<?php echo $form->error($model, $code); ?>
				</div>
				<? endif; ?>
				<? break; ?>
		<? endswitch; ?>
	<? endforeach; ?>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? "Добавить" : "Сохранить"); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->