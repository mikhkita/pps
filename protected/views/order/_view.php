<div class="b-order-form">
	<script>
		var points = JSON.parse('<?=json_encode(CHtml::listData(Point::model()->sorted()->findAll(), "id", "is_airport"))?>');
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

		$points = CHtml::listData(Point::model()->sorted()->findAll(), "id", "name");
		?>

		<?php echo $form->errorSummary($model); ?>

		<div class="b-form b-order-form-main">
			<div class="b-tile">
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($model, "start_point_id"); ?>
					</div>
					<div class="b-hor-input-right">
						<?=$model->startPoint->name?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($model, "end_point_id"); ?>
					</div>
					<div class="b-hor-input-right">
						<?=$model->endPoint->name?>
					</div>
				</div>
				<div class="b-hor-input date-airplane hide">
					<div class="b-input b-hor-input-left">
						<label for="Order_flight" class="required"><?=$labels["flight_id"]?> <span class="required">*</span></label>
					</div>
					<div class="b-hor-input-right">
						<?=$model->flight->name?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<label for="Order_to_date" class="required"><span class="date-bus"><?=$labels["to_date"]?></span><span class="date-airplane hide">Дата/время вылета рейса</span></label>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?=$model->to_date?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<label for="Order_from_date" class="required"><span class="date-bus"><?=$labels["from_date"]?></span><span class="date-airplane hide">Дата/время прилета рейса</span></label>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?=$model->from_date?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<label for="person_count" class="required">Количество пассажиров <span class="required">*</span></label>
					</div>
					<div class="b-hor-input-right">
						<input type="hidden" id="person_count" value="<?=count($model->persons)?>">
						<?=count($model->persons)?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($model, "comment"); ?>
					</div>
					<div class="b-hor-input-right">
						<?=$model->comment?>
					</div>
				</div>
			</div>
		</div>

		<div class="b-order-for-person" id="b-order-for-person">

		<?  foreach ($model->persons as $key => $person): ?>
		<div class="b-form b-order-form-person" id="person-form-<?=$person->id?>">
			<h5 class="grey">Пассажир №<span class="b-person-index"><?=$person->number?></span></h5>
			<div class="b-tile">
				<div class="b-order-form-fio">
					<h3><?=$person->last_name?> <?=$person->name?> <?=$person->third_name?></h3>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "is_child"); ?>
					</div>
					<div class="b-hor-input-right">
						<?=$person->age?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "direction_id"); ?>
					</div>
					<div class="b-hor-input-right">
						<?=$person->direction?>
					</div>
				</div>
				<div class="b-hor-input b-transfer-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "transfer_id"); ?>
					</div>
					<div class="b-hor-input-right">
						<?=$person->transfer?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "pay_himself"); ?>
					</div>
					<div class="b-hor-input-right">
						<?=$person->payment?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "phone"); ?>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?=$person->phone?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "address"); ?>
					</div>
					<div class="b-hor-input-right">
						<?=$person->address?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "passport"); ?>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?=$person->passport?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "birthday"); ?>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?=$person->birthday?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "comment"); ?>
					</div>
					<div class="b-hor-input-right">
						<?=$person->comment?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "cash"); ?>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?=CHTML::textField("Person[<?=$person->id?>][cash]", $person->cash, array("maxlength" => 32, "placeholder" => "...", "class" => "numeric"))?>
					</div>
				</div>
				<div class="b-price-row">
					<div class="b-label-block b-person-price" data-price="<?=$person->price?>">
						<label>Итого:</label>
						<h3><?=number_format( $person->price, 0, ',', '&nbsp;' )?> ₽</h3>
					</div>
				</div>
			</div>
		</div>
		<? endforeach;  ?>

		</div>

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
					<span class="b-count resizable-font-item big-resizable-font-item" id="totalSum"></span><span class="b-count-text resizable-font-item" id="totalSumText">рублей</span>
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