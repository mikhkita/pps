<a href="<?php echo $this->createUrl("/".$this->adminMenu["cur"]->code."/adminIndex")?>" class="b-back">Назад</a>
<h1>Редактирование платежа</h1>

<?php $this->renderPartial("_form", array("payment" => $payment)); ?>