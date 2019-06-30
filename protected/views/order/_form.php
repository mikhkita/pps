<div class="b-order-form b-order-form-edit">
	<script>
		var points = JSON.parse('<?=json_encode(CHtml::listData(Point::model()->sorted()->findAll(), "id", "is_airport"))?>'),
			priceList = JSON.parse('<?=json_encode(Price::getPriceList())?>');
	</script>
	<div class="b-order-form-left">
		<?php $form=$this->beginWidget("CActiveForm", array(
			"id" => "order-form",
			"enableAjaxValidation" => false,
			'htmlOptions'=>array(
				'class'=>'validatable',
		    ),
		)); 
		$labels = $model->attributeLabels();
		?>

		<?php echo $form->errorSummary($model); ?>

		<div class="b-form b-order-form-main">
			<div class="b-tile">
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($model, "start_point_id"); ?>
					</div>
					<div class="b-hor-input-right">
						<?php echo $form->dropDownList($model, "start_point_id", array("" => "Не выбрано") + CHtml::listData(Point::model()->sorted()->active()->startAvailable()->findAll(), "id", "name"), array("class" => "select2", "required" => true, "title" => "Поле обязательно")); ?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($model, "end_point_id"); ?>
					</div>
					<div class="b-hor-input-right">
						<?php echo $form->dropDownList($model, "end_point_id", array("" => "Не выбрано") + CHtml::listData(Point::model()->sorted()->active()->endAvailable()->findAll(), "id", "name"), array("class" => "select2", "required" => true, "title" => "Поле обязательно")); ?>
					</div>
				</div>
				<div class="b-hor-input date-airplane hide">
					<div class="b-input b-hor-input-left">
						<label for="Order_to_flight_id" class="required"><?=$labels["to_flight_id"]?> <span class="required">*</span></label>
					</div>
					<div class="b-hor-input-right">
						<?php echo $form->dropDownList($model, "to_flight_id", array("" => "Не выбрано") + CHtml::listData(Flight::model()->sorted()->active()->findAll(), "id", "name"), array("class" => "select2", "required" => true, "title" => "Поле обязательно")); ?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<label for="Order_to_date" class="required"><span class="date-bus"><?=$labels["to_date"]?></span><span class="date-airplane hide">Дата/время вылета рейса</span></label>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?php echo $form->textField($model, "to_date", array("maxlength" => 32, "autocomplete" => "off", "placeholder" => "...", "class" => "date-time", "title" => "Поле обязательно")); ?>
					</div>
				</div>
				<div class="b-hor-input date-airplane hide">
					<div class="b-input b-hor-input-left">
						<label for="Order_from_flight_id" class="required"><?=$labels["from_flight_id"]?> <span class="required">*</span></label>
					</div>
					<div class="b-hor-input-right">
						<?php echo $form->dropDownList($model, "from_flight_id", array("" => "Не выбрано") + CHtml::listData(Flight::model()->sorted()->active()->findAll(), "id", "name"), array("class" => "select2", "required" => true, "title" => "Поле обязательно")); ?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<label for="Order_from_date" class="required"><span class="date-bus"><?=$labels["from_date"]?></span><span class="date-airplane hide">Дата/время прилета рейса</span></label>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?php echo $form->textField($model, "from_date", array("maxlength" => 32, "autocomplete" => "off", "placeholder" => "...", "class" => "date-time", "title" => "Поле обязательно")); ?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<label for="person_count" class="required">Количество пассажиров <span class="required">*</span></label>
					</div>
					<div class="b-hor-input-right">
						<input type="text" class="numeric" id="person_count" value="1" placeholder="...">
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

		<div class="b-add-person-cont">
			<a href="#" class="b-add-btn icon-add" id="b-add-person-btn">Добавить пассажира</a>
		</div>

		<? /* for( $index = 0; $index < 0; $index ++ ): ?>
		<div class="b-form b-order-form-person">
			<h5 class="grey">Пассажир №1</h5>
			<div class="b-tile">
				<div class="b-order-form-fio">
					<div class="b-resize-input">
						<div class="input-buffer"></div>
						<input type="text" required name="Person[<?=$index?>][last_name]" autocomplete="off" placeholder="Фамилия" title="ФИО обязательно для заполнения">
					</div>
					<div class="b-resize-input">
						<div class="input-buffer"></div>
						<input type="text" required name="Person[<?=$index?>][name]" autocomplete="off" placeholder="Имя" title="ФИО обязательно для заполнения">
					</div>
					<div class="b-resize-input">
						<div class="input-buffer"></div>
						<input type="text" name="Person[<?=$index?>][third_name]" autocomplete="off" placeholder="Отчество">
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
						<?=CHTML::textField("Person[".$index."][phone]", "", array("maxlength" => 32, "autocomplete" => "off", "required" => true, "placeholder" => "...", "class" => "phone", "title" => "Поле обязательно"))?>
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
						<?=CHTML::textArea("Person[".$index."][address]", "", array("maxlength" => 1024, "autocomplete" => "off", "placeholder" => "...", "rows" => 1, "required" => true))?>
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
		<? endfor; */ ?>

	<?php $this->endWidget(); ?>		
	</div>
	<div class="b-order-form-right">
		<div class="b-tile">
			<div class="b-right-tile-top-block b-right-tile-block">
				Итого:
			</div>
			<div class="b-right-tile-middle-block b-right-tile-block">
				<div class="b-right-tile-block-string b-pass-text-container">
					<span class="b-count resizable-font-item big-resizable-font-item" id="person_total_count">1</span><span class="b-count-text resizable-font-item" id="totalPassText">пассажир</span>
				</div>
				<div class="b-right-tile-block-string b-sum-text-container">
					<span class="b-count resizable-font-item big-resizable-font-item" id="totalSum">0</span><span class="b-count-text resizable-font-item" id="totalSumText">рублей</span>
				</div>
			</div>
			<div class="b-right-tile-bottom-block b-right-tile-block">
				<a href="#" class="b-btn" id="b-progress-bar-container">
					<span class="icon-check main-text">Оформить заявку</span>
				</a>
				<div class="b-btn-text-cont">
					<span class="process">Отправка заявки,<br>пожалуйста подождите...</span>
					<span class="icon-check success">Заявка успешно отправлена</span>
					<span class="error">Ошибка! Проверьте интернет-соединение и попробуйте ещё раз</span>
				</div>
			</div>
		</div>
	</div>

</div><!-- form -->


<script id="person-template" type="text/x-handlebars-template">
  	<div class="b-form b-order-form-person" id="person-form-{{index}}">
		<h5 class="grey">Пассажир №<span class="b-person-index">0</span></h5>
		<div class="b-tile">
			<div class="b-order-form-fio">
				<div class="b-resize-input">
					<div class="input-buffer"></div>
					<input type="text" required name="Person[{{index}}][last_name]" placeholder="Фамилия *" class="not-remove" title="ФИО обязательно">
				</div>
				<div class="b-resize-input">
					<div class="input-buffer"></div>
					<input type="text" required name="Person[{{index}}][name]" placeholder="Имя *" class="not-remove" title="ФИО обязательно">
				</div>
				<div class="b-resize-input">
					<div class="input-buffer"></div>
					<input type="text" name="Person[{{index}}][third_name]" placeholder="Отчество" class="not-remove">
				</div>
				<a href="#" class="b-remove-btn right icon-delete">Удалить</a>
			</div>
			<div class="b-hor-input">
				<div class="b-input b-hor-input-left">
					<?php echo $form->labelEx($person, "is_child"); ?>
				</div>
				<div class="b-hor-input-right">
					<?=CHTML::radioButtonList("Person[{{index}}][is_child]", 0, $person->ages, array("template" => '<div class="b-radio">{input}{label}</div>', "separator" => "", "class" => "is_child-input", "container" => "div", "baseID" => "child_{{index}}")); ?>
				</div>
			</div>
			<div class="b-hor-input">
				<div class="b-input b-hor-input-left">
					<?php echo $form->labelEx($person, "direction_id"); ?>
				</div>
				<div class="b-hor-input-right">
					<?=CHTML::radioButtonList("Person[{{index}}][direction_id]", 1, $person->directions, array("template" => '<div class="b-radio">{input}{label}</div>', "separator" => "", "container" => "div", "class" => "direction-field", "baseID" => "person_{{index}}")); ?>
				</div>
			</div>
			<div class="b-hor-input b-transfer-input">
				<div class="b-input b-hor-input-left">
					<?php echo $form->labelEx($person, "transfer_id"); ?>
				</div>
				<div class="b-hor-input-right">
					<?=CHTML::radioButtonList("Person[{{index}}][transfer_id]", 1, $person->transfers, array("template" => '<div class="b-radio not-remove">{input}{label}</div>', "separator" => "", "container" => "div", "baseID" => "transfer_{{index}}")); ?>
				</div>
			</div>
			<div class="b-hor-input">
				<div class="b-input b-hor-input-left">
					<?php echo $form->labelEx($person, "pay_himself"); ?>
				</div>
				<div class="b-hor-input-right">
					<?=CHTML::radioButtonList("Person[{{index}}][pay_himself]", 0, $person->payments, array("template" => '<div class="b-radio not-remove">{input}{label}</div>', "separator" => "", "container" => "div", "baseID" => "pay_himself_{{index}}")); ?>
				</div>
			</div>
			<div class="b-hor-input">
				<div class="b-input b-hor-input-left">
					<?php echo $form->labelEx($person, "phone"); ?>
				</div>
				<div class="b-hor-input-right b-to-datepicker">
					<?=CHTML::textField("Person[{{index}}][phone]", "", array("maxlength" => 32, "required" => true, "placeholder" => "...", "class" => "phone not-remove", "title" => "Поле обязательно", "autocomplete" => "off"))?>
				</div>
			</div>
			<div class="b-hor-input">
				<div class="b-input b-hor-input-left">
					<?php echo $form->labelEx($person, "address"); ?>
				</div>
				<div class="b-hor-input-right">
					<?=CHTML::textArea("Person[{{index}}][address]", "", array("maxlength" => 1024, "placeholder" => "...", "class" => "not-remove", "rows" => 1, "required" => true))?>
				</div>
			</div>
			<div class="b-hor-input">
				<div class="b-input b-hor-input-left">
					<?php echo $form->labelEx($person, "passport"); ?>
				</div>
				<div class="b-hor-input-right b-to-datepicker">
					<?=CHTML::textField("Person[{{index}}][passport]", "", array("maxlength" => 12, "placeholder" => "...", "class" => "passport not-remove", "title" => "Поле обязательно"))?>
				</div>
			</div>
			<div class="b-hor-input">
				<div class="b-input b-hor-input-left">
					<?php echo $form->labelEx($person, "birthday"); ?>
				</div>
				<div class="b-hor-input-right b-to-datepicker">
					<?=CHTML::textField("Person[{{index}}][birthday]", "", array("maxlength" => 32, "placeholder" => "...", "class" => "date not-remove", "title" => "Поле обязательно", "autocomplete" => "off"))?>
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
				<div class="b-label-block b-person-price">
					<input type="hidden" class="price-input" name="Person[{{index}}][price]" value="0">
					<input type="hidden" class="one_way_price-input" name="Person[{{index}}][one_way_price]" value="0">
					<input type="hidden" class="commission-input" name="Person[{{index}}][commission]" value="0">
					<label>Итого:</label>
					<h3 class="icon-rub-bold"><span>0</span></h3>
				</div>
			</div>
		</div>
	</div>
</script>