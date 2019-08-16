<div class="b-right-tile-top-block b-right-tile-block">
	<h1 class="icon-check">Счёт успешно создан</h1>
</div>
<div class="b-right-tile-middle-block b-right-tile-block">
	<div class="b-right-tile-block-string b-popup-payment-text">
		Номер счета: <span class="bold" id="account_num">0</span>
	</div>
	<div class="b-right-tile-block-string b-popup-payment-text">
		Дата создания счета: <span class="bold" id="account_date">0</span>
	</div>
	<div class="b-right-tile-block-string b-popup-payment-text">
		Сумма к оплате: <span class="bold" id="account_sum">0</span>
	</div>
</div>
<div class="b-right-tile-bottom-block b-right-tile-block">
	<a href="<?=$this->createUrl("/payment/index")?>" class="b-btn b-btn-green">
		<span class="b-btn-popup-text">Перейти в список платежей</span>
	</a>
</div>