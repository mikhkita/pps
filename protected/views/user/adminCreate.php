<div class="b-popup">
	<h1>Добавление пользователя</h1>

	<?php $this->renderPartial("_form", array("model" => $model, "roles" => array(), "roleList" => $roleList)); ?>
</div>