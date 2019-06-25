<div class="b-popup">
	<h1>Редактирование <?=$this->adminMenu["items"][ $_GET["class"] ]->rod_name?></h1>

	<?php $this->renderPartial("_form", array("model" => $model, "fields" => $fields)); ?>
</div>