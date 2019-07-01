<a href="<?php echo $this->createUrl("/".$this->adminMenu["cur"]->code."/adminIndex")?>" class="b-back">Назад</a>
<h1>Новая заявка</h1>

<?php $this->renderPartial("_form", array("model" => $model, "person" => $person, "default_payment_type_id" => $default_payment_type_id)); ?>