<div class="b-order-form b-back-form">
	<div class="b-order-form-left">
		<? 
		// $btnText = ($payment->type_id == 1) ? "Оплатить онлайн" : "Выставить счет" ; 
		?>
		<?php $form=$this->beginWidget("CActiveForm", array(
			"id" => "order-form",
			"enableAjaxValidation" => false,
			'htmlOptions'=>array(
				'class'=>'validatable',
		    ),
		)); 
		$prevOrder = NULL;
		?>

		<?php echo $form->errorSummary($back); ?>
		<div class="b-form b-order-form-main">
			<div class="b-tile">
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<?php echo $form->labelEx($back, "reason"); ?>
					</div>
					<div class="b-hor-input-right">
						<?php echo $form->textArea($back, "reason", array("maxlength" => 1024, "required" => true, "placeholder" => "...", "rows" => 1)); ?>
					</div>
				</div>
			</div>
		</div>

		<? foreach ($persons as $key => $person):
			$nextPerson = $persons[$key + 1];
			// $person->person->price_without_commission
			?>

		<? if( $prevOrder->id != $person->order_id ): ?>	
		<div class="" id="b-order-for-person">
			<div class="b-form b-order-form-person" id="person-form-0">
				<h5 class="grey"><?=$person->order->getTitle()?></h5>
				<div class="b-tile">
					<? endif; ?>
					<div class="b-hor-input">
						<div class="b-input b-hor-input-left">
							<p><?=$person->fio?></p>
						</div>
						<input type="hidden" class="price-input">
						<div class="b-hor-input-right b-payment-inputs clearfix">
						<? if( $person->direction_id == 1 ): ?>
							<?=CHTML::radioButtonList("BackPerson[".$person->id."][direction_id]", $person->direction_id, $person->directions, array("template" => '<div class="b-radio">{input}{label}</div>', "separator" => "", "container" => "div", "class" => "direction-field", "baseID" => "person_".$person->id)); ?>
						<? else: ?>
							<div><?=$person->direction?></div>
						<? endif; ?>
						</div>
					</div>
					<? if( !isset($nextPerson) || $nextPerson->order_id != $person->order_id ): ?>
				</div>
			</div>
		</div>
			<? endif; ?>
		<? $prevOrder = $person->order; 
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
					<span class="b-count resizable-font-item big-resizable-font-item" id="person_total_count"><?=count($persons)?></span><span class="b-count-text resizable-font-item" id="totalPassText">пассажир</span>
				</div>
			</div>
			<div class="b-right-tile-bottom-block b-right-tile-block">
				<a href="#" class="b-btn" id="b-progress-bar-container">
					<span class="icon-check main-text">Оформить отмену</span>
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