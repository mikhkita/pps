<div class="b-order-form">

	<div class="b-order-form-left">
		<?php $form=$this->beginWidget("CActiveForm", array(
			"id" => "faculties-form",
			"enableAjaxValidation" => false,
			'htmlOptions'=>array(
				'class'=>'validatable',
		    ),
		)); ?>

		<?php echo $form->errorSummary($model); ?>

		<div class="b-form b-order-form-main">
			<div class="b-tile">
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($model, "date"); ?>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?php echo $form->textField($model, "date", array("maxlength" => 32, "required" => true, "placeholder" => "...", "class" => "date", "title" => "Поле обязательно")); ?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($model, "start_point_id"); ?>
					</div>
					<div class="b-hor-input-right">
						<?php echo $form->dropDownList($model, "start_point_id", array("" => "Не выбрано") + CHtml::listData(Point::model()->sorted()->findAll(), "id", "name"), array("class" => "select2", "required" => true, "title" => "Поле обязательно")); ?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($model, "end_point_id"); ?>
					</div>
					<div class="b-hor-input-right">
						<?php echo $form->dropDownList($model, "end_point_id", array("" => "Не выбрано") + CHtml::listData(Point::model()->sorted()->findAll(), "id", "name"), array("class" => "select2", "required" => true, "title" => "Поле обязательно")); ?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($model, "flight"); ?>
					</div>
					<div class="b-hor-input-right">
						<?php echo $form->textField($model, "flight", array("maxlength" => 32, "required" => true, "placeholder" => "...", "title" => "Поле обязательно")); ?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<label for="passenger_count" class="required">Количество пассажиров <span class="required">*</span></label>
					</div>
					<div class="b-hor-input-right">
						<input type="text" class="numeric" id="passenger_count" value="1" placeholder="...">
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($model, "comment"); ?>
					</div>
					<div class="b-hor-input-right">
						<?php echo $form->textArea($model, "comment", array("maxlength" => 1024, "placeholder" => "...", "rows" => 1)); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="b-order-for-person" id="b-order-for-person"></div>

		<? for( $index = 0; $index < 0; $index ++ ): ?>
		<div class="b-form b-order-form-person">
			<h5 class="grey">Пассажир №1</h5>
			<div class="b-tile">
				<div class="b-order-form-fio">
					<div class="b-resize-input">
						<div class="input-buffer"></div>
						<input type="text" required name="Person[<?=$index?>][last_name]" placeholder="Фамилия" title="ФИО обязательно для заполнения">
					</div>
					<div class="b-resize-input">
						<div class="input-buffer"></div>
						<input type="text" required name="Person[<?=$index?>][name]" placeholder="Имя" title="ФИО обязательно для заполнения">
					</div>
					<div class="b-resize-input">
						<div class="input-buffer"></div>
						<input type="text" name="Person[<?=$index?>][third_name]" placeholder="Отчество">
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "is_child"); ?>
					</div>
					<div class="b-hor-input-right">
						<?=CHTML::radioButtonList("Person[".$index."][is_child]", 0, array( 0 => "Старше 10 лет", 1 => "Младше 10 лет" ), array("template" => '<div class="b-radio">{input}{label}</div>', "separator" => "", "container" => "div", "baseID" => "child_".$index)); ?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "direction_id"); ?>
					</div>
					<div class="b-hor-input-right">
						<?=CHTML::radioButtonList("Person[".$index."][direction_id]", 0, array( 0 => "В обе стороны", 1 => "Туда", 2 => "Обратно" ), array("template" => '<div class="b-radio">{input}{label}</div>', "separator" => "", "container" => "div", "baseID" => "person_".$index)); ?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "phone"); ?>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?=CHTML::textField("Person[".$index."][phone]", "", array("maxlength" => 32, "required" => true, "placeholder" => "...", "class" => "phone", "title" => "Поле обязательно"))?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "transfer_id"); ?>
					</div>
					<div class="b-hor-input-right">
						<?=CHTML::radioButtonList("Person[".$index."][transfer_id]", 0, array( 0 => "Самостоятельно", 1 => "На такси" ), array("template" => '<div class="b-radio">{input}{label}</div>', "separator" => "", "container" => "div", "baseID" => "transfer_".$index)); ?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "address"); ?>
					</div>
					<div class="b-hor-input-right">
						<?=CHTML::textArea("Person[".$index."][address]", "", array("maxlength" => 1024, "placeholder" => "...", "rows" => 1, "required" => true))?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "comment"); ?>
					</div>
					<div class="b-hor-input-right">
						<?=CHTML::textArea("Person[".$index."][comment]", "", array("maxlength" => 1024, "placeholder" => "...", "rows" => 1))?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "cash"); ?>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?=CHTML::textField("Person[".$index."][cash]", "", array("maxlength" => 32, "placeholder" => "...", "class" => "numeric"))?>
					</div>
				</div>
				<div class="b-price-row">
					<div class="b-label-block b-person-price" data-price="4500">
						<label>Итого:</label>
						<h3>4 500 ₽</h3>
					</div>
				</div>
			</div>
		</div>
		<? endfor; ?>

		<div class="row buttons">
			<?php echo CHtml::submitButton($model->isNewRecord ? "Добавить" : "Сохранить"); ?>
			<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
		</div>

	<?php $this->endWidget(); ?>		
	</div>
	<div class="b-tile b-order-form-right">
		<div class="b-tile sticky">
			<div class="b-right-tile-top-block b-right-tile-block">
				Итого:
			</div>
			<div class="b-right-tile-middle-block b-right-tile-block">
				<div class="b-right-tile-block-string b-pass-text-container">
					<span class="b-count resizable-font-item big-resizable-font-item" id="passenger_total_count">1</span><span class="b-count-text resizable-font-item" id="totalPassText">пассажир</span>
				</div>
				<div class="b-right-tile-block-string b-sum-text-container">
					<span class="b-count resizable-font-item big-resizable-font-item" id="totalSum">0</span><span class="b-count-text resizable-font-item" id="totalSumText">рублей</span>
				</div>
			</div>
			<div class="b-right-tile-bottom-block b-right-tile-block">
				<a href="#" class="b-btn" id="b-progress-bar-container">
					<span class="icon-check">Оформить заявку</span>
				</a>
			</div>
		</div>
	</div>

</div><!-- form -->


<script id="person-template" type="text/x-handlebars-template">
  	<div class="b-form b-order-form-person" id="person-form-{{index}}">
		<h5 class="grey">Пассажир №{{number}}</h5>
		<div class="b-tile">
			<div class="b-order-form-fio">
				<div class="b-resize-input">
					<div class="input-buffer"></div>
					<input type="text" required name="Person[{{index}}][last_name]" placeholder="Фамилия" class="not-remove" title="ФИО обязательно для заполнения">
				</div>
				<div class="b-resize-input">
					<div class="input-buffer"></div>
					<input type="text" required name="Person[{{index}}][name]" placeholder="Имя" class="not-remove" title="ФИО обязательно для заполнения">
				</div>
				<div class="b-resize-input">
					<div class="input-buffer"></div>
					<input type="text" name="Person[{{index}}][third_name]" placeholder="Отчество" class="not-remove">
				</div>
			</div>
			<div class="b-hor-input">
				<div class="b-input b-hor-input-left">
					<?php echo $form->labelEx($person, "is_child"); ?>
				</div>
				<div class="b-hor-input-right">
					<?=CHTML::radioButtonList("Person[{{index}}][is_child]", 0, array( 0 => "Старше 10 лет", 1 => "Младше 10 лет" ), array("template" => '<div class="b-radio">{input}{label}</div>', "separator" => "", "container" => "div", "baseID" => "child_{{index}}")); ?>
				</div>
			</div>
			<div class="b-hor-input">
				<div class="b-input b-hor-input-left">
					<?php echo $form->labelEx($person, "direction_id"); ?>
				</div>
				<div class="b-hor-input-right">
					<?=CHTML::radioButtonList("Person[{{index}}][direction_id]", 0, array( 0 => "В обе стороны", 1 => "Туда", 2 => "Обратно" ), array("template" => '<div class="b-radio">{input}{label}</div>', "separator" => "", "container" => "div", "baseID" => "person_{{index}}")); ?>
				</div>
			</div>
			<div class="b-hor-input">
				<div class="b-input b-hor-input-left">
					<?php echo $form->labelEx($person, "phone"); ?>
				</div>
				<div class="b-hor-input-right b-to-datepicker">
					<?=CHTML::textField("Person[{{index}}][phone]", "", array("maxlength" => 32, "required" => true, "placeholder" => "...", "class" => "phone not-remove", "title" => "Поле обязательно"))?>
				</div>
			</div>
			<div class="b-hor-input">
				<div class="b-input b-hor-input-left">
					<?php echo $form->labelEx($person, "transfer_id"); ?>
				</div>
				<div class="b-hor-input-right">
					<?=CHTML::radioButtonList("Person[{{index}}][transfer_id]", 0, array( 0 => "Самостоятельно", 1 => "На такси" ), array("template" => '<div class="b-radio not-remove">{input}{label}</div>', "separator" => "", "container" => "div", "baseID" => "transfer_{{index}}")); ?>
				</div>
			</div>
			<div class="b-hor-input">
				<div class="b-input b-hor-input-left">
					<?php echo $form->labelEx($person, "address"); ?>
				</div>
				<div class="b-hor-input-right">
					<?=CHTML::textArea("Person[{{index}}][address]", "", array("maxlength" => 1024, "placeholder" => "...", "rows" => 1, "required" => true))?>
				</div>
			</div>
			<div class="b-hor-input">
				<div class="b-input b-hor-input-left">
					<?php echo $form->labelEx($person, "comment"); ?>
				</div>
				<div class="b-hor-input-right">
					<?=CHTML::textArea("Person[{{index}}][comment]", "", array("maxlength" => 1024, "placeholder" => "...", "rows" => 1))?>
				</div>
			</div>
			<div class="b-hor-input">
				<div class="b-input b-hor-input-left">
					<?php echo $form->labelEx($person, "cash"); ?>
				</div>
				<div class="b-hor-input-right b-to-datepicker">
					<?=CHTML::textField("Person[{{index}}][cash]", "", array("maxlength" => 32, "placeholder" => "...", "class" => "numeric"))?>
				</div>
			</div>
			<div class="b-price-row">
				<div class="b-label-block b-person-price" data-price="4500">
					<label>Итого:</label>
					<h3>4 500 ₽</h3>
				</div>
			</div>
		</div>
	</div>
</script>