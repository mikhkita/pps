<div class="b-order-form b-payment-form">
	<div class="b-order-form-left">
		<? $btnText = ($payment->type_id == 1) ? "Оплатить онлайн" : "Выставить счет" ; ?>
		<?php $form=$this->beginWidget("CActiveForm", array(
			"id" => "order-form",
			"enableAjaxValidation" => false,
			'htmlOptions'=>array(
				'class'=>'validatable',
		    ),
		)); 
		$labels = $payment->attributeLabels();
		$prevOrder = NULL;
		?>

		<?php echo $form->errorSummary($payment); ?>

		<? foreach ($payment->persons as $key => $person):
			$nextPerson = $payment->persons[$key + 1];
			// $person->person->price_without_commission
			?>

		<? if( $prevOrder->id != $person->person->order_id ): ?>	
		<div class="" id="b-order-for-person">
			<div class="b-form b-order-form-person" id="person-form-0">
				<h5 class="grey"><?=$person->person->order->getTitle()?></h5>
				<div class="b-tile">
					<? endif; ?>
					<div class="b-hor-input">
						<div class="b-input b-hor-input-left">
							<p><?=$person->person->fio?></p>
						</div>
						<div class="b-hor-input-right b-payment-inputs clearfix" data-max-price="<?=$person->person->price_without_commission?>">
						<? if( $person->person->direction_id == 1 ): ?>
							<?=CHTML::radioButtonList("PaymentPerson[".$person->person_id."][direction_id]", $person->direction_id, $person->person->directions, array("template" => '<div class="b-radio">{input}{label}</div>', "separator" => "", "container" => "div", "class" => "direction-field", "baseID" => "person_".$person->person_id)); ?>
						<? else: ?>
							<div><?=$person->person->direction?></div>
						<? endif; ?>
							<div class="b-right-price">
								<?=CHTML::textField("PaymentPerson[".$person->person_id."][sum]", $person->sum, array("maxlength" => 32, "required" => true, "placeholder" => "...", "class" => "numeric price-input", "title" => "Поле обязательно", "autocomplete" => "off"))?> ₽
							</div>
						</div>
					</div>
					<? if( !isset($nextPerson) || $nextPerson->person->order_id != $person->person->order_id ): ?>
					<div class="b-price-row">
						<div class="b-label-block b-order-price">
							<label>Итого:</label>
							<h3><span>0</span> ₽</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
			<? endif; ?>
		<? $prevOrder = $person->person->order; 
		   endforeach; ?>

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
					<span class="icon-check main-text"><?=$btnText?></span>
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