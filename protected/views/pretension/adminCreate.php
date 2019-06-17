<div class="b-popup">
	<h1>Добавление <?=$this->adminMenu["cur"]->rod_name?></h1>

	<?php $this->renderPartial("_form", array("model" => $model, "stakeholders" => array(), "section" => $section)); ?>
</div>