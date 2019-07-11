<?php

class ExchangeController extends Controller
{
	public function filters()
	{
		return array(
			"accessControl"
		);
	}

	public function accessRules()
	{
		return array(
			array("allow",
				"actions" => array("importDictionaries", "exportOrder", "exportBack", "exportPayments", "importOrders"),
				"users" => array("*"),
			),
			array("deny",
				"users" => array("*"),
			),
		);
	}

	public function actionExportBack(){

	// <Отмена ИД="12">
	// 	<Заявка Номер="123123123">
	// 	 <Пассажир ПассажирКод="00-00029283" НомерСтроки="1"/>
	// 	</Заявка>
	// </Отмена>

		function getarBack(){
			
			$model = Back::model()->findAll("export_date is NULL");
			$arBack = array();

			if (!empty($model)) {
				foreach ($model as $key => $back) {
					$arBack[$key]["ID"] = $back->id;
					foreach ($back->persons as $backPerson) {

						if ($backPerson->person->direction_id == 1 || $backPerson->person->direction_id == 2) {
							$arBack[$key]["ORDERS"][$backPerson->person->order->to_code_1c][] = array(
								'CODE' => $backPerson->person->code_1c,
								'NUMBER' => $backPerson->person->number
							);
						}

						if ($backPerson->person->direction_id == 1 || $backPerson->person->direction_id == 3) {
							$arBack[$key]["ORDERS"][$backPerson->person->order->from_code_1c][] = array(
								'CODE' => $backPerson->person->code_1c,
								'NUMBER' => $backPerson->person->number
							);
						}

					}
				}
			}

			echo "<pre>";
			var_dump($arBack);
			echo "</pre>";

			return $arBack;
		}

		function addDocumentToXML(&$xml, $arBack){

			$document = $xml->addChild("Отмена");

			foreach ($arBack as $code => $back) {
				$document->addAttribute("ИД", $back['ID']);
				foreach ($back['ORDERS'] as $orderID => $orderInfo) {
					$order = $document->addChild("Заказ");
					$order->addAttribute("Номер", $orderID);
					foreach($orderInfo as $passengers){
						$passenger = $order->addChild("Пассажир");
						$passenger->addAttribute("ПассажирКод", $passengers['CODE']);
						$passenger->addAttribute("НомерСтроки", $passengers['NUMBER']);
					}
				}
			}

			file_put_contents("backs.xml", $xml->asXML());
			
			$model = Back::model()->findAll("export_date is NULL");

			$date = date("Y-m-d H:i:s");

			foreach ($model as $key => $back) {
				$back->export_date = $date;
				$back->save();
			}
		}

		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Отмены/>');

		$arBack = getarBack();

		if (!empty($arBack)) {
			addDocumentToXML($xml, $arBack);
		}

	}

	public function actionExportPayments(){
		
		function getArPayments(){
			
			$model = Payments::model()->findAll();
			$arPayments = array();

			if (!empty($model)) {
				foreach ($model as $key => $payment) {
					
					$arPayments[$key]["ID"] = $payment->id;
					$arPayments[$key]["AGENCY_ID"] = $payment->user->agency->id;
					$arPayments[$key]["NUMBER"] = $payment->number;
					$arPayments[$key]["DATE"] = $payment->date;
					$arPayments[$key]["TRANSACTION"] = $payment->transaction;
					$arPayments[$key]["TYPE"] = $payment->payments->name_1c;

					foreach ($back->persons as $backPerson) {

						if ($backPerson->person->direction_id == 1 || $backPerson->person->direction_id == 2) {
							$arPayments[$key]["ORDERS"][$backPerson->person->order->to_code_1c][] = array(
								'CODE' => $backPerson->person->code_1c,
								'NUMBER' => $backPerson->person->number
							);
						}

						if ($backPerson->person->direction_id == 1 || $backPerson->person->direction_id == 3) {
							$arPayments[$key]["ORDERS"][$backPerson->person->order->from_code_1c][] = array(
								'CODE' => $backPerson->person->code_1c,
								'NUMBER' => $backPerson->person->number
							);
						}

					}
				}
			}

			echo "<pre>";
			var_dump($arPayments);
			echo "</pre>";

			return $arPayments;
		}

		function addDocumentToXML(&$xml, $arPayments){

			$document = $xml->addChild("Отмена");

			foreach ($arBack as $code => $back) {
				$document->addAttribute("ИД", $back['ID']);
				foreach ($back['ORDERS'] as $orderID => $orderInfo) {
					$order = $document->addChild("Заказ");
					$order->addAttribute("Номер", $orderID);
					foreach($orderInfo as $passengers){
						$passenger = $order->addChild("Пассажир");
						$passenger->addAttribute("ПассажирКод", $passengers['CODE']);
						$passenger->addAttribute("НомерСтроки", $passengers['NUMBER']);
					}
				}
			}

			file_put_contents("backs.xml", $xml->asXML());
			
			$model = Back::model()->findAll("export_date is NULL");

			$date = date("Y-m-d H:i:s");

			foreach ($model as $key => $back) {
				$back->export_date = $date;
				$back->save();
			}
		}

		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Отмены/>');

		$arBack = getarBack();

		if (!empty($arBack)) {
			addDocumentToXML($xml, $arBack);
		}
	}

	public function actionExportOrder(){

		// $test_array = array (
		//   'bla' => 'blub',
		//   'foo' => 'bar',
		//   'another_array' => array (
		//     'stack' => 'overflow',
		//   ),
		// );
		// $xml = new SimpleXMLElement('<root/>');
		// array_walk_recursive($test_array, array ($xml, 'addChild'));
		// print $xml->asXML();
		// die();

		function addDocumentToXML(&$xml, $order, $persons){
			// var_dump($document);
			// var_dump($passengers);
			// echo "\n=============\n";

			$document = $xml->addChild("Документ");
			foreach ($order as $code => $field) {
				$document->addAttribute($code, $field);
			}

			$passengers = $document->addChild("Пассажиры");
			foreach ($persons as $key => $person) {
				$passenger = $passengers->addChild("Пассажир");

				foreach ( $person as $code => $field) {
					$passenger->addAttribute($code, $field);
				}
			}
		}

		function addOrderToXML(&$xml, $order){
			$orderFields = array(
				"ИД" => $order->id,
				"Дата" => $order->create_date,
				"НазваниеАгентства" => $order->user->agency->code_1c,
				"ИсходТочка" => "",
				"КонТочка" => "",
				"Рейс" => "",
				"ДатаВылетаРейса" => "",
				"ДатаПрилетаРейса" => "",
				"ДатаВыезда" => "",
				"ДатаПриезда" => "",
				"ТипЗаказа" => "",
				"Комментарий" => $order->comment,
			);

			$personsTo = array();
			$personsFrom = array();
			foreach ($order->persons as $key => $person) {
				$personFields = array(
					"Серия" => "",
					"НомерПаспорта" => "",
					"ВидДокумента" => "",
					"ФИО" => $person->fio,
					"ИДПассажира" => $person->id,
					// "ПассажирКод" => "",
					"ДатаРождения" => $person->birthday,
					"Телефон" => $person->phone,
					"Адрес" => $person->address,
					"ТипПассажира" => ($person->is_child)?"Детский":"Взрослый",
					"Цена" => "",
					"Комментарий" => $person->comment,
					"СпособОплаты" => "Безналичный",
					"СостояниеИсполнения" => "В работе",
					"СтатусОплаты" => "Не оплачен",
				);

				if( !empty($person->passport) ){
					$tmp = explode(" ", $person->passport);
					if( count( $tmp ) == 3 ){
						$personFields["Серия"] = $tmp[0]." ".$tmp[1];
						$personFields["НомерПаспорта"] = $tmp[2];
						$personFields["ВидДокумента"] = "Паспорт гражданина РФ";
					}
				}

				$personFields["СпособОплаты"] = $person->paymentType;

				switch ($person->direction_id) {
					case 1:
						$personFields["Цена"] = number_format($person->to_price, 2, '.', '');
						array_push($personsTo, $personFields);

						$personFields["Цена"] = number_format($person->from_price, 2, '.', '');
						array_push($personsFrom, $personFields);
						break;
					case 2:
						$personFields["Цена"] = number_format($person->to_price, 2, '.', '');
						array_push($personsTo, $personFields);
						break;
					case 3:
						$personFields["Цена"] = number_format($person->from_price, 2, '.', '');
						array_push($personsFrom, $personFields);
						break;
				}
			}

			if( count($personsTo) ){
				$orderFieldsTo = $orderFields;

				$orderFieldsTo["ИсходТочка"] = $order->startPoint->code_1c;
				$orderFieldsTo["КонТочка"] = $order->endPoint->code_1c;
				$orderFieldsTo["ТипЗаказа"] = "Вылет";
				$orderFieldsTo["ИД"] = $orderFieldsTo["ИД"]."-1";

				if( !empty($order->flightTo) ){
					$orderFieldsTo["Рейс"] = $order->flightTo->code_1c;
				}

				if( !empty($order->to_date) ){
					$orderFieldsTo[ ( !empty($orderFieldsTo["Рейс"]) )?"ДатаВылетаРейса":"ДатаВыезда" ] = $order->to_date;
				}

				addDocumentToXML($xml, $orderFieldsTo, $personsTo);
			}

			if( count($personsFrom) ){
				$orderFieldsFrom = $orderFields;

				$orderFieldsFrom["ИсходТочка"] = $order->endPoint->code_1c;
				$orderFieldsFrom["КонТочка"] = $order->startPoint->code_1c;
				$orderFieldsFrom["ТипЗаказа"] = "Прилет";
				$orderFieldsFrom["ИД"] = $orderFieldsFrom["ИД"]."-2";

				if( !empty($order->flightFrom) ){
					$orderFieldsFrom["Рейс"] = $order->flightFrom->code_1c;
				}

				if( !empty($order->from_date) ){
					$orderFieldsFrom[ ( !empty($orderFieldsFrom["Рейс"]) )?"ДатаПрилетаРейса":"ДатаПриезда" ] = $order->from_date;
				}

				addDocumentToXML($xml, $orderFieldsFrom, $personsFrom);
			}
		}

		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Документы/>');

		$order = Order::model()->findByPk(15);
		$order = addOrderToXML($xml, $order);

		// $order = Order::model()->findByPk(2);
		// $order = addOrderToXML($xml, $order);
		

		// array_walk_recursive($test_array, array ($xml, 'addChild'));
		// print $xml->asXML();
		file_put_contents("example.xml", $xml->asXML());

		// var_dump($order);
	}

	public function actionImportDictionaries($partial = false){
		$filename = Yii::app()->basePath."/../1c_exchange/dictionaries.xml";

		$xml = simplexml_load_file($filename);

		// Импорт точек маршрута
		foreach( $xml->ТочкиМаршрута->{"Элемент.ТочкиМаршрута"} as $item ){
			$code_1c = trim( $item->Код );
			$name = trim( $item->Наименование );
			$active = ( trim( $item->ПометкаУдаления ) == "Нет" )?1:0;

			$model = Point::model()->find("code_1c = '".$code_1c."'");

			if( !$model ){
				$model = new Point;
			}

			$model->code_1c = $code_1c;
			$model->name = $name;
			$model->active = $active;

			if( !$model->save() ){
				print_r($model->getErrors());
			}
		}

		// Импорт рейсов
		foreach( $xml->Рейсы->{"Элемент.Рейсы"} as $item ){
			$code_1c = trim( $item->Код );
			$name = trim( $item->Наименование );
			$active = ( trim( $item->ПометкаУдаления ) == "Нет" )?1:0;

			$model = Flight::model()->find("code_1c = '".$code_1c."'");

			if( !$model ){
				$model = new Flight;
			}

			$model->code_1c = $code_1c;
			$model->name = $name;
			$model->active = $active;

			if( !$model->save() ){
				print_r($model->getErrors());
			}
		}

		// Импорт турагентств
		foreach( $xml->Партнеры->{"Элемент.Партнеры"} as $item ){
			$code_1c = trim( $item->Код );
			$name = trim( $item->НаименованиеПолное );
			$active = ( trim( $item->ПометкаУдаления ) == "Нет" )?1:0;

			$model = Agency::model()->find("code_1c = '".$code_1c."'");

			if( !$model ){
				$model = new Agency;
			}

			$model->code_1c = $code_1c;
			$model->name = $name;
			$model->active = $active;

			if( !$model->save() ){
				print_r($model->getErrors());
			}
		}

		// Индексирование точек маршрута по коду 1С
		$model = Point::model()->findAll();
		$points = array();
		foreach ($model as $key => $point) {
			$points[ $point->code_1c ] = $point->id;
		}

		// Импорт стоимостей
		foreach( $xml->СтоимостьПроезда->{"Элемент.СтоимостьПроезда"} as $item ){
			$is_child = ( trim( $item->ТипПассажира ) == "Детский" )?1:0;
			$start_point_id = $points[ trim( $item->НачТочка ) ];
			$end_point_id = $points[ trim( $item->КонТочка ) ];
			$total_price = intval( trim( $item->ЦенаПроездаОбеСтороны ) );
			$one_way_price = intval( trim( $item->ЦенаПроезда ) );
			$date = date("Y-m-d H:i:s", strtotime( $item->Период ));

			if( empty($start_point_id) || empty($end_point_id) ){
				echo "Нет точки маршрута\n";
				continue;
			}

			$model = Price::model()->find("is_child = '".$is_child."' AND start_point_id = '".$start_point_id."' AND end_point_id = '".$end_point_id."'");

			if( !$model ){
				$model = new Price;
			}

			$model->is_child = $is_child;
			$model->start_point_id = $start_point_id;
			$model->end_point_id = $end_point_id;
			$model->total_price = $total_price;
			$model->one_way_price = $one_way_price;
			$model->date = $date;

			if( !$model->save() ){
				print_r($model->getErrors());
			}
		}

		// Импорт комиссий
		foreach( $xml->КомиссияАгентств->{"Элемент.КомиссияАгентств"} as $item ){
			$is_child = ( trim( $item->ТипПассажира ) == "Детский" )?1:0;
			$start_point_id = $points[ trim( $item->НачТочка ) ];
			$end_point_id = $points[ trim( $item->КонТочка ) ];
			$is_percent = ( trim( $item->Комиссия ) == "Комиссия %" )?1:0;
			$commission = intval( trim( $item->ЧислоКомиссии ) );

			if( empty($start_point_id) || empty($end_point_id) ){
				echo "Нет точки маршрута\n";
				continue;
			}

			$model = Price::model()->find("is_child = '".$is_child."' AND start_point_id = '".$start_point_id."' AND end_point_id = '".$end_point_id."'");

			if( !$model ){
				echo $item->ТипПассажира." ";
				echo $item->НачТочка." ";
				echo $item->КонТочка."<br>";
				// echo "Нет стоимости<br>";
				continue;
			}

			$model->is_percent = $is_percent;
			$model->commission = $commission;

			if( !$model->save() ){
				print_r($model->getErrors());
			}
		}
	}

	public function actionImportOrders($partial = false){
		$filename = Yii::app()->basePath."/../1c_exchange/orders.xml";

		$xml = simplexml_load_file($filename);

		// Импорт заказов
		$result = array();
		foreach( $xml->Документ as $documentObj ){
			$document = array();
			$related = array();
			foreach($documentObj->attributes() as $a => $b) {
				$document[$a] = trim($b);
			}
			foreach($documentObj->ЗаказПривязанный->attributes() as $a => $b) {
				$related[$a] = trim($b);
			}

			// $document["ИД"] = trim($document["ИД"]);
			// $document["Дата"] = trim($document["Дата"]);
			// $document["Номер"] = trim($document["Номер"]);
			// $document["НазваниеАгентства"] = trim($document["НазваниеАгентства"]);
			// $document["ИсходТочка"] = trim($document["ИсходТочка"]);
			// $document["КонТочка"] = trim($document["КонТочка"]);
			// $document["Рейс"] = trim($document["Рейс"]);
			// $document["ДатаПрилетаРейса"] = trim($document["ДатаПрилетаРейса"]);
			// $document["ДатаВылетаРейса"] = trim($document["ДатаВылетаРейса"]);
			// $document["ДатаВыезда"] = trim($document["ДатаВыезда"]);
			// $document["ДатаПриезда"] = trim($document["ДатаПриезда"]);
			// $document["ТипЗаказа"] = trim($document["ТипЗаказа"]);

			if( !empty($document["Рейс"]) ){
				$flight = Flight::model()->find("code_1c = '".$document["Рейс"]."'");
				if( !$flight ){
					$result[ $document["Номер"] ] = "Error: Не найден рейс с кодом \"".$document["Рейс"]."\"";
					continue;
				}
			}

			$startPoint = Point::model()->find("code_1c = '".$document["ИсходТочка"]."'");
			if( !$startPoint ){
				$result[ $document["Номер"] ] = "Error: Не найдена точка маршрута с кодом \"".$document["ИсходТочка"]."\"";
				continue;
			}

			$endPoint = Point::model()->find("code_1c = '".$document["КонТочка"]."'");
			if( !$endPoint ){
				$result[ $document["Номер"] ] = "Error: Не найдена точка маршрута с кодом \"".$document["КонТочка"]."\"";
				continue;
			}

			$agency = Agency::model()->find("code_1c = '".$document["НазваниеАгентства"]."'");
			if( !$agency ){
				$result[ $document["Номер"] ] = "Error: Не найдено агентство с кодом \"".$document["НазваниеАгентства"]."\"";
				continue;
			}
			if( !count($agency->users) ){
				$result[ $document["Номер"] ] = "Error: Не найден пользователь у агентства с кодом  \"".$document["НазваниеАгентства"]."\"";
				continue;	
			}else{
				$user = array_pop($agency->users);
			}

			$order = Order::model()->find(( ($document["ТипЗаказа"] == "Вылет")?"to_code_1c":"from_code_1c" )." = '".$document["Номер"]."'");
			if( !$order ){
				$order = Order::model()->find(( ($document["ТипЗаказа"] == "Вылет")?"from_code_1c":"to_code_1c" )." = '".$related["НомерЗаказПривязанный"]."'");
				if( !$order ){
					$order = new Order();
				}
			}

			$isNewOrder = $order->isNewRecord;

			$order->create_date = $document["Дата"];
			$order->export_date = date(time());

			if( $document["ТипЗаказа"] == "Вылет" ){
				$order->start_point_id = $startPoint->id;
				$order->end_point_id = $endPoint->id;
				$order->to_date = ( !empty($document["ДатаВылетаРейса"]) )?$document["ДатаВылетаРейса"]:$document["ДатаВыезда"];
				$order->to_flight_id = $flight->id;
				$order->to_code_1c = $document["Номер"];
			}else{
				$order->start_point_id = $endPoint->id;
				$order->end_point_id = $startPoint->id;
				$order->from_date = ( !empty($document["ДатаПрилетаРейса"]) )?$document["ДатаПрилетаРейса"]:$document["ДатаПриезда"];
				$order->from_flight_id = $flight->id;
				$order->from_code_1c = $document["Номер"];
			}

			// Проверяем есть ли взрослые в заявки и есть ли дети в заявке
			$issetChild = false;
			$issetAdult = false;
			foreach ($documentObj->Пассажиры->Пассажир as $key => $passengerObj) {
				$passenger = array();
				foreach($passengerObj->attributes() as $a => $b) {
					$passenger[$a] = trim($b);
				}
				if( $passenger["ТипПассажира"] == "Детский" ){
					$issetChild = true;
				}else{
					$issetAdult = true;
				}
			}

			if( $issetChild ){
				// Комиссия за ребенка
				$priceChild = Price::model()->find("start_point_id = '".$startPoint->id."' AND end_point_id = '".$endPoint->id."' AND is_child = '1'");
				if( !$priceChild ){
					$result[ $document["Номер"] ] = "Error: Не найден маршрут для детей \"".$document["ИсходТочка"]." – ".$document["КонТочка"]."\"";
					continue;
				}else if( empty($priceChild->commission) ){
					$result[ $document["Номер"] ] = "Error: У маршрута не задана комиссия за детский билет по маршруту \"".$document["ИсходТочка"]." – ".$document["КонТочка"]."\"";
					continue;
				}
			}

			if( $issetAdult ){
				// Комиссия за взрослого
				$priceAdult = Price::model()->find("start_point_id = '".$startPoint->id."' AND end_point_id = '".$endPoint->id."' AND is_child = '0'");
				if( !$priceAdult ){
					$result[ $document["Номер"] ] = "Error: Не найден маршрут для взрослых \"".$document["ИсходТочка"]." – ".$document["КонТочка"]."\"";
					continue;
				}else if( empty($priceAdult->commission) ){
					$result[ $document["Номер"] ] = "Error: У маршрута не задана комиссия за взрослый билет по маршруту \"".$document["ИсходТочка"]." – ".$document["КонТочка"]."\"";
					continue;
				}
			}

			$direction_id = ($document["ТипЗаказа"] == "Вылет")?2:3;
			foreach ($documentObj->Пассажиры->Пассажир as $key => $passengerObj) {
				$passenger = array();
				foreach($passengerObj->attributes() as $a => $b) {
					$passenger[$a] = trim($b);
				}
				// $passenger["Серия"] = trim( $passenger["Серия"] );
				// $passenger["НомерПаспорта"] = trim( $passenger["НомерПаспорта"] );
				// $passenger["ВидДокумента"] = trim( $passenger["ВидДокумента"] );
				// $passenger["ФИО"] = trim( $passenger["ФИО"] );
				// $passenger["ИДПассажира"] = trim( $passenger["ИДПассажира"] );
				// $passenger["ПассажирКод"] = trim( $passenger["ПассажирКод"] );
				// $passenger["НомерСтроки"] = trim( $passenger["НомерСтроки"] );
				// $passenger["ДатаРождения"] = trim( $passenger["ДатаРождения"] );
				// $passenger["Телефон"] = trim( $passenger["Телефон"] );
				// $passenger["Адрес"] = trim( $passenger["Адрес"] );
				// $passenger["ТипПассажира"] = trim( $passenger["ТипПассажира"] );
				// $passenger["Цена"] = trim( $passenger["Цена"] );
				// $passenger["Комментарий"] = trim( $passenger["Комментарий"] );
				// $passenger["СпособОплаты"] = trim( $passenger["СпособОплаты"] );
				// $passenger["СостояниеИсполнения"] = trim( $passenger["СостояниеИсполнения"] );
				// $passenger["СтатусОплаты"] = trim( $passenger["СтатусОплаты"] );

				if( !$isNewOrder ){
					$person = Person::model()->find("code_1c = '".$passenger["ПассажирКод"]."' AND order_id = '".$order->id."' AND number = '".$passenger["НомерСтроки"]."'");
				}

				if( !$person ){
					$person = new Person();
				}

				// Если способ оплаты не указан, то задаем оплата по карте
				$status_id = array_search($passenger["СостояниеИсполнения"], $person->statuses);
				if( $status_id === false ){
					$result[ $document["Номер"] ] = "Error: Неизвестное Состояние Исполнения: \"".$passenger["СостояниеИсполнения"]."\"";
					continue;	
				}

				// Если способ оплаты не указан, то задаем оплата по карте
				$payment_type_id = array_search($passenger["СпособОплаты"], $person->paymentTypes);
				if( $payment_type_id === false ){
					$payment_type_id = 1;
				}

				if( $person->fio != $passenger["ФИО"] ){
					$person->name = $passenger["ФИО"];
					$person->third_name = NULL;
					$person->last_name = NULL;
				}
				$person->order_id = $order->id;
				$person->is_child = ( $passenger["ТипПассажира"] == "Детский" )?1:0;
				$person->phone = Controller::convertPhoneNumber($passenger["Телефон"]);
				$person->comment = $passenger["Комментарий"];
				$person->address = $passenger["Адрес"];
				$person->transfer_id = 0;
				$person->passport = ( !empty($passenger["Серия"]) && !empty($passenger["НомерПаспорта"]) )?($passenger["Серия"]." ".$passenger["НомерПаспорта"]):NULL;
				$person->birthday = $passenger["ДатаРождения"];
				$person->payment_type_id = $payment_type_id;
				$person->code_1c = $passenger["ПассажирКод"];
				$person->number = $passenger["НомерСтроки"];

				if( $document["ТипЗаказа"] == "Вылет" ){
					$person->to_status_id = $status_id;
					$person->to_price = intval( $passenger["Цена"] );
				}else{
					$person->from_status_id = $status_id;
					$person->from_price = intval( $passenger["Цена"] );
				}

				if( $person->direction_id != 1 ){
					if( $person->isNewRecord ){
						$person->direction_id = $direction_id;
					}else{
						if( $person->direction_id != $direction_id ){
							$person->direction_id = 1;
						}
					}
				}

				$priceObj = ( $passenger["ТипПассажира"] == "Детский" )?$priceChild:$priceAdult;
				if( $priceObj->is_percent ){
					$person->commission = (intval($person->to_price) + intval($person->from_price)) / 100 * $priceObj->commission;
				}else{
					if( $person->direction_id == 1 ){
						$person->commission = $priceObj->commission*2;
					}else{
						$person->commission = $priceObj->commission;
					}
				}

				if( !$person->save() ){
					print_r($person->getErrors());
				}
			}

			$order->user_id = $user->id;

			if( !$order->save() ){
				print_r($order->getErrors());
			}else{
				$result[ $document["Номер"] ] = "Success";
			}
		}

		// echo "<pre>";
		var_dump($result);
		// echo "</pre>";
	}
}
