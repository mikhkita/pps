<a href="<?php echo $this->createUrl("/".$this->adminMenu["cur"]->code."/adminIndex")?>" class="b-back">Назад</a>
<h1><?=$title?></h1>

<?php $this->renderPartial("_form", array("payment" => $payment)); ?>