<a href="<?php echo $this->createUrl("/order/index")?>" class="b-back">Назад</a>
<h1>Оформление заявки на отмену</h1>

<?php $this->renderPartial("_form", array("back" => $back, "persons" => $persons)); ?>