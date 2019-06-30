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

				<? if( !empty($model->to_flight_id) ): ?>
				<div class="b-hor-input date-airplane">
					<div class="b-input b-hor-input-left">
						<label for="Order_flight" class="required"><?=$labels["to_flight_id"]?> <span class="required">*</span></label>
					</div>
					<div class="b-hor-input-right">
						<?=$model->flightTo->name?>
					</div>
				</div>
				<? endif; ?>

				<? if( !empty($model->to_date) ): ?>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<label for="Order_to_date" class="required"><? if( !empty($model->to_flight_id) ): ?>Дата/время вылета рейса<?else:?><?=$labels["to_date"]?><? endif; ?></label>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?=$model->to_date?>
					</div>
				</div>
				<? endif; ?>

				<? if( !empty($model->from_flight_id) ): ?>
				<div class="b-hor-input date-airplane">
					<div class="b-input b-hor-input-left">
						<label for="Order_flight" class="required"><?=$labels["from_flight_id"]?> <span class="required">*</span></label>
					</div>
					<div class="b-hor-input-right">
						<?=$model->flightFrom->name?>
					</div>
				</div>
				<? endif; ?>

				<? if( !empty($model->from_date) ): ?>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<label for="Order_from_date" class="required"><? if( !empty($model->from_flight_id) ): ?>Дата/время прилета рейса<?else:?><?=$labels["from_date"]?><? endif; ?></span></label>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?=$model->from_date?>
					</div>
				</div>
				<? endif; ?>

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

				<? if( !empty($person->passport) ): ?>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "passport"); ?>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?=$person->passport?>
					</div>
				</div>
				<? endif; ?>

				<? if( !empty($person->birthday) ): ?>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "birthday"); ?>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?=$person->birthday?>
					</div>
				</div>
				<? endif; ?>

				<? if( !empty($person->comment) ): ?>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "comment"); ?>
					</div>
					<div class="b-hor-input-right">
						<?=$person->comment?>
					</div>
				</div>
				<? endif; ?>

				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($person, "cash"); ?>
					</div>
					<div class="b-hor-input-right b-to-datepicker">
						<?=CHTML::textField("Person[".$person->id."][cash]", $person->cash, array("maxlength" => 32, "placeholder" => "...", "class" => "numeric"))?>
					</div>
				</div>
				<div class="b-price-row">
					<div class="b-label-block b-person-price">
						<label>Итого:</label>
						<input type="hidden" class="price-input" value="<?=$person->price?>">
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
					<span class="icon-check main-text">Сохранить</span>
				</a>
				<div class="b-btn-text-cont">
					<span class="process">Сохранение заявки,<br>пожалуйста подождите...</span>
					<span class="icon-check success">Заявка успешно сохранена</span>
					<span class="error">Ошибка! Проверьте интернет-соединение и попробуйте ещё раз</span>
				</div>
			</div>
		</div>
	</div>

</div><!-- form -->