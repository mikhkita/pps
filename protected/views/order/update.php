<a href="<?php echo $this->createUrl("/".$this->adminMenu["cur"]->code."/index")?>" class="b-back">Назад</a>
<h1><?=$model->getTitle()?></h1>

<?php $this->renderPartial("_view", array("model" => $model)); ?>