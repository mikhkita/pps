<!DOCTYPE html>
<html xml:lang="ru" lang="ru">
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
    <meta name="format-detection" content="telephone=no">
	<meta name="language" content="ru" />
	<title><?php echo $this->pageTitle; ?></title>
    <meta name="viewport" id="viewport" content="width=device-width, user-scalable=no">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"> 
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.qtip.min.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/reset.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.fancybox.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/preloader.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/select2.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/layout.css" />
    <? if($this->isMobile): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin-mobile.css" />
    <? endif; ?>

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.qtip.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/plupload/plupload.full.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tinymce/jquery.tinymce.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/select2.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/KitProgress.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/autosize.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/handlebars-v4.1.2.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.fancybox.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/KitResizableInput.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/sticky.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/notify.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/KitProgressBtn.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.maskedinput.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/numericInput.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/TweenMax.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/admin.js"></script>
    <?php foreach ($this->scripts AS $script): ?><script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/<?php echo $script?>.js"></script><? endforeach; ?>
</head>
<body<? if($this->isMobile): ?> class="is-mobile"<? endif; ?>>
    <a href="#" class="b-burger b-round-btn icon-menu"></a>
    <div class="b-for-image-form"></div>
    <? if( Yii::app()->user->isGuest ): ?>
        <?php echo $content;?>
    <? else: ?>
        <div class="b-header clearfix">
            <a href="<?php echo $this->createUrl('site/logout')?>" class="b-menu-tile">
                <span class="b-icon icon-logout"></span>
                <p>Выйти</p>
            </a>
            <h3>Меню</h3>
            <?foreach ($this->adminMenu["items"] as $i => $menuItem):?>
                <? if( $menuItem->rule == NULL || Yii::app()->user->checkAccess($menuItem->rule) ): ?>
                    <a href="<?php echo $this->createUrl('/'.$menuItem->code.'/adminindex')?>" class="b-menu-tile" data-name="<?=$menuItem->code?>">
                        <span class="b-icon icon-<?=$menuItem->code?>"></span>
                        <p><?=$menuItem->menu_name?></p>
                    </a>
                <? endif; ?>
            <?endforeach;?>

            <? if(Yii::app()->params['debug']): ?>
                <div class="b-debug"><?=$this->debugText?></div>
            <? endif; ?>
        </div>
        <div class="main">
            <div class="b-main-center">
                <?php echo $content;?>
                <? if(Yii::app()->user->checkAccess("debug") && Yii::app()->params['debug']): ?>
                    <br>
                    <? 
                        list($queryCount, $queryTime) = Yii::app()->db->getStats();
                        echo "Кол-во запросов: $queryCount, Общее время запросов: ".sprintf('%0.5f',$queryTime)."s";
                    ?>
                <? endif; ?>
            </div>
        </div>
        <div class="b-menu-overlay"></div>
    <? endif; ?>
    <div style="display: none;">
        <div class="b-popup b-popup-delete" id="b-popup-delete">
            <h1>Вы действительно хотите удалить запись?</h1>
            <div class="row buttons">
                <input type="button" class="b-delete-yes" value="Да">
                <input type="button" onclick="$.fancybox.close();" value="Нет">
            </div>
        </div>
    </div>
</body>
</html>
