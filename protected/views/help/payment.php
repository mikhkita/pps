<h1><?=$this->adminMenu["cur"]->name?></h1>
<div class="b-text">
	<h3>Создание платежей</h3>
	<br>
	<p>Для добавления заявки необходимо перейти в раздел «Заявки» и нажать кнопку «Добавить заявку», расположенную в верхнем правом углу (Рисунок 1).</p>
	<br>
	<a href="<?php echo Yii::app()->request->baseUrl; ?>/i/help/1.jpg" class="fancy-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/i/help/1.jpg" alt=""></a>
	<p class="tc">Рисунок 1</p>
	<br>
	<p>После этого откроется форма для добавления заявки (Рисунок 2). Обязательные для заполнения поля отмечены звёздочками (*). Для создания заявки требуется заполнить все обязательные поля. По мере заполнения кнопка «Оформить заявку» будет плавно изменять свой цвет с серого на зелёный.</p>
	<br>
	<a href="<?php echo Yii::app()->request->baseUrl; ?>/i/help/2.jpg" class="fancy-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/i/help/2.jpg" alt=""></a>
	<p class="tc">Рисунок 2</p>
	<br>
	<p>Если заполнены не все обязательные поля, то при нажатии на кнопку «Оформить заявку» либо на подчеркнутый текст под кнопкой «Оформить заявку» будут подсвечены незаполненные поля, обязательные к заполнению (Рисунок 3).</p>
	<br>
	<a href="<?php echo Yii::app()->request->baseUrl; ?>/i/help/3.jpg" class="fancy-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/i/help/3.jpg" alt=""></a>
	<p class="tc">Рисунок 3</p>
	<br>
	<p>Как только все обязательные поля будут заполнены, а кнопка станет зелёного цвета, появится возможность создания текущей заявки (Рисунок 4).</p>
	<br>
	<a href="<?php echo Yii::app()->request->baseUrl; ?>/i/help/4.jpg" class="fancy-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/i/help/4.jpg" alt=""></a>
	<p class="tc">Рисунок 4</p>
	<br>
	<p>Для выбора пункта отправления следует нажать на текст «Не выбрано» в поле «Откуда». После этого необходимо либо выбрать нужный пункт из списка доступных пунктов отправления (Рисунок 5), либо написать название нужного пункта отправления в появившееся поле (Рисунок 6).</p>
	<br>
	<a href="<?php echo Yii::app()->request->baseUrl; ?>/i/help/5.jpg" class="fancy-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/i/help/5.jpg" alt=""></a>
	<p class="tc">Рисунок 5</p>
	<br>
	<a href="<?php echo Yii::app()->request->baseUrl; ?>/i/help/6.jpg" class="fancy-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/i/help/6.jpg" alt=""></a>
	<p class="tc">Рисунок 6</p>
	<br>
	<p>Пункт прибытия выбирается тем же способом. После выбора пункта отправления и пункта прибытия выполняется расчёт стоимости проезда (Рисунок 7).</p>
	<br>
	<a href="<?php echo Yii::app()->request->baseUrl; ?>/i/help/7.jpg" class="fancy-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/i/help/7.jpg" alt=""></a>
	<p class="tc">Рисунок 7</p>
	<br>
	<p>Дата в поле «Дата/время выезда» заполняется либо вводом с клавиатуры, либо с помощью выпадающего календаря (Рисунок 8). Серым цветом подсвечивается текущая дата. Чёрным цветом – выбранная дата (Рисунок 9). Ввод времени в поле «Дата/время выезда» осуществляется с клавиатуры.</p>
	<br>
	<a href="<?php echo Yii::app()->request->baseUrl; ?>/i/help/8.jpg" class="fancy-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/i/help/8.jpg" alt=""></a>
	<p class="tc">Рисунок 8</p>
	<br>
	<a href="<?php echo Yii::app()->request->baseUrl; ?>/i/help/9.jpg" class="fancy-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/i/help/9.jpg" alt=""></a>
	<p class="tc">Рисунок 9</p>
	<br>
	<p>Количество пассажиров указывается в поле «Количество пассажиров». Для добавления или удаления пассажиров необходимо нажать на кнопку «Удалить» или «Добавить пассажира» соответственно (Рисунок 10). После добавления или удаления пассажира происходит перерасчет стоимости.</p>
	<br>
	<a href="<?php echo Yii::app()->request->baseUrl; ?>/i/help/10.jpg" class="fancy-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/i/help/10.jpg" alt=""></a>
	<p class="tc">Рисунок 10</p>
	<br>
	<p>Поля «Комментарий», «Телефон», «Адрес», «Серия и номер паспорта», «День рождения», «Получено» заполняются вводом с клавиатуры. Поле «Получено» создано для записи суммы в рублях, которую клиент уже оплатил (ввод только чисел). Оно необходимо для ведения внутреннего учета.</p>
	<br>
	<p>После того, как будут заполнены все обязательные поля, появится возможность создать заявку (Рисунок 4). По нажатию на кнопку «Оформить заявку» будет создана заявка и появится соответствующее сообщение (Рисунок 11). После этого произойдет переход на страницу со списком всех заявок.</p>
	<br>
	<a href="<?php echo Yii::app()->request->baseUrl; ?>/i/help/11.jpg" class="fancy-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/i/help/11.jpg" alt=""></a>
	<p class="tc">Рисунок 11</p>
	<br>

</div>