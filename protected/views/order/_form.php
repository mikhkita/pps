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

		<div class="b-form b-order-form-person">
			<h5 class="grey">Пассажир №1</h5>
			<div class="b-tile">
				<div class="b-order-form-fio">
					<div class="b-resize-input">
						<input type="text" name="last_name" placeholder="Фамилия">
						<div class="input-buffer"></div>
					</div>
					<div class="b-resize-input">
						<input type="text" name="name" placeholder="Имя">
						<div class="input-buffer"></div>
					</div>
					<div class="b-resize-input">
						<input type="text" name="third_name" placeholder="Отчество">
						<div class="input-buffer"></div>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<label for="date" class="required">Дата поездки <span class="required">*</span></label>
					</div>
					<div class="b-hor-input-right">
						<input type="text" id="date" placeholder="...">
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<label for="way" class="required">Направление <span class="required">*</span></label>
					</div>
					<div class="b-hor-input-right">
						<?php echo $form->dropDownList($model, "start_point_id", array(0 => "Не выбрано") + CHtml::listData(Point::model()->sorted()->findAll(), "id", "name"), array("class" => "select2")); ?>
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<label for="flight_number" class="required">Номер рейса <span class="required">*</span></label>
					</div>
					<div class="b-hor-input-right">
						<input type="text" id="flight_number" placeholder="...">
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
						<label for="comment" class="required">Комментарий <span class="required">*</span></label>
					</div>
					<div class="b-hor-input-right">
						<input type="text" id="comment" placeholder="...">
					</div>
				</div>
				<div class="b-hor-input">
					<div class="b-input b-hor-input-left">
						<label class="required">Итого</label>
					</div>
					<div class="b-hor-input-right">
						<span class="b-person-price" data-price="1900">1900</span>
					</div>
				</div>
			</div>
		</div>

		<div class="row buttons">
			<?php echo CHtml::submitButton($model->isNewRecord ? "Добавить" : "Сохранить"); ?>
			<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
		</div>

	<?php $this->endWidget(); ?>		
	</div>
	<div class="b-tile b-order-form-right">
		<div class="b-tile">
			<div class="b-right-tile-top-block b-right-tile-block">
				Итого
			</div>
			<div class="b-right-tile-middle-block b-right-tile-block">
				<div class="b-right-tile-block-string">
					<span class="b-count" id="passenger_total_count">1</span><span class="b-count-text" id="totalPassText">пассажир</span>
				</div>
				<div class="b-right-tile-block-string">
					<span class="b-count" id="totalSum">0</span><span class="b-count-text" id="totalSumText">рублей</span>
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