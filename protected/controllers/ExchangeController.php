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
				"actions" => array("importDictionaries", "exportOrders", "exportBack", "exportPayments", "importOrders", "ordersResult"),
				"users" => array("*"),
			),
			array("deny",
				"users" => array("*"),
			),
		);
	}

	public function actionOrdersResult(){
		// $filename = Yii::app()->basePath."/../1c_exchange/result.xml";
		$filename = Controller::readFileFromInput("result");

		$xml = simplexml_load_file($filename);
		if( !$xml ){
			Debug::log("Пустой файл или не XML-файл", true);
			die();
		}

		$result = array();
		
		foreach( $xml->Документ as $documentObj ){
			$document = array();
			foreach($documentObj->attributes() as $a => $b) {
				$document[$a] = trim($b);
			}
			$tmp = explode("-", $document["ИД"]);
			$id = intval($tmp[0]);
			$type = intval($tmp[1]);

			$order = Order::model()->findByPk($id);

			if( !$order ){
				$result[ $document["Номер"] ] = "Error: Не найден заказ с ID \"".$id."\"";
				continue;
			}

			if( !empty($document["Номер"]) ){
				if( $type == 1 ){
					$order->to_code_1c = $document["Номер"];
				}else{
					$order->from_code_1c = $document["Номер"];
				}
			}else{
				if( !empty($document["Причина"]) ){
					if( !empty($order->reason_1c) ){
						$order->reason_1c = $order->reason_1c.";<br>";
					}
					$order->reason_1c = $order->reason_1c.( ( $type == 1 )?"Туда: ":"Обратно: " ).$document["Причина"];
				}
				$order->canceled = 1;
			}

			$order->save();

			foreach ($documentObj->Пассажиры->Пассажир as $key => $passengerObj) {
				$passenger = array();
				foreach($passengerObj->attributes() as $a => $b) {
					$passenger[$a] = trim($b);
				}
				$person = Person::model()->findByPk($passenger["ИДПассажира"]);

				if( !$person ){
					$result[ $document["Номер"] ] = "Error: Не найден пассажир с ID \"".$id."\"";
					continue;
				}

				$status_id = array_search($passenger["СостояниеИсполнения"], $person->statuses);
				if( $status_id === false ){
					$result[ $document["Номер"] ] = "Error: Неизвестное Состояние Исполнения: \"".$passenger["СостояниеИсполнения"]."\"";
					continue;	
				}

				if( $type == 1 ){
					$person->to_status_id = $status_id;
				}else{
					$person->from_status_id = $status_id;
				}

				$person->code_1c = $passenger["ПассажирКод"];
				$person->save();
			}
		}
	}

	public function actionExportBack(){

	// <Отмена ИД="12">
	// 	<Заявка Номер="123123123">
	// 	 <Пассажир ПассажирКод="00-00029283" НомерСтроки="1"/>
	// 	</Заявка>
	// </Отмена>

		function getArBack(){
			
			$model = Back::model()->findAll("export_date is NULL");
			$arBack = array();

			if (!empty($model)) {
				foreach ($model as $key => $back) {
					$arBack[$key]["ID"] = $back->id;
					$arBack[$key]["ORDERS"] = array();

					foreach ($back->persons as $backPerson) {

						if ($backPerson->person->direction_id == 1 || $backPerson->person->direction_id == 2) {
							
							if (!isset($arBack[$key]["ORDERS"][$backPerson->person->order->to_code_1c])) {
								$arBack[$key]["ORDERS"][$backPerson->person->order->to_code_1c] = array();
							}

							$arBack[$key]["ORDERS"][$backPerson->person->order->to_code_1c][] = array(
								'CODE' => $backPerson->person->code_1c,
								'NUMBER' => $backPerson->person->number
							);
						}

						if ($backPerson->person->direction_id == 1 || $backPerson->person->direction_id == 3) {

							if (!isset($arBack[$key]["ORDERS"][$backPerson->person->order->from_code_1c])) {
								$arBack[$key]["ORDERS"][$backPerson->person->order->from_code_1c] = array();
							}

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

			foreach ($arBack as $code => $back) {
				$document = $xml->addChild("Отмена");
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

			// file_put_contents("backs.xml", $xml->asXML());
			
			Back::model()->updateAll(array(
				"export_date" => date("Y-m-d H:i:s")
			), "export_date is NULL");

			var_dump($xml->asXML());
		}

		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Отмены/>');

		$arBack = getArBack();

		if (!empty($arBack)) {
			addDocumentToXML($xml, $arBack);
		}

	}

	public function actionExportPayments(){
		$exportedIds = array();

		function getArPayments(&$exportedIds){
			$model = Payment::model()->findAll("export_date is NULL");
			$arPayments = array();

			if (!empty($model)) {
				foreach ($model as $key => $payment) {
					$arPayments[$key] = array(
						"ID" => $payment->id,
						"AGENCY_ID" => $payment->user->agency->code_1c,
						"NUMBER" => $payment->number,
						"DATE" => $payment->date,
						"TRANSACTION" => $payment->transaction,
						"TYPE" => $payment->type->name_1c,
						"ORDERS" => array(),
					);

					$error = false;
					foreach ($payment->persons as $paymentPerson) {
						if( $error ){
							continue;
						}

						if( empty($paymentPerson->person->code_1c) ){
							$error = true;
							continue;
						}

						if ($paymentPerson->direction_id == 1 || $paymentPerson->direction_id == 2){
							if( empty($paymentPerson->person->order->to_code_1c) ){
								$error = true;
								continue;
							}

							if (!isset($arPayments[$key]["ORDERS"][$paymentPerson->person->order->to_code_1c])) {
								$arPayments[$key]["ORDERS"][$paymentPerson->person->order->to_code_1c] = array(
									'DATE' => $paymentPerson->person->order->create_date,
									'PERSONS' => array()
								);
							}

							$arPayments[$key]["ORDERS"][$paymentPerson->person->order->to_code_1c]['PERSONS'][] = array(
								'CODE' => $paymentPerson->person->code_1c,
								'NUMBER' => $paymentPerson->person->number,
								'SUM' => $paymentPerson->person->to_price_without_commission,
							);
						}

						if ($paymentPerson->direction_id == 1 || $paymentPerson->direction_id == 3) {
							if( empty($paymentPerson->person->order->from_code_1c) ){
								$error = true;
								continue;
							}

							if (!isset($arPayments[$key]["ORDERS"][$paymentPerson->person->order->from_code_1c])) {
								$arPayments[$key]["ORDERS"][$paymentPerson->person->order->from_code_1c] = array(
									'DATE' => $paymentPerson->person->order->create_date,
									'PERSONS' => array()
								);
							}

							$arPayments[$key]["ORDERS"][$paymentPerson->person->order->from_code_1c]['PERSONS'][] = array(
								'CODE' => $paymentPerson->person->code_1c,
								'NUMBER' => $paymentPerson->person->number,
								'SUM' => $paymentPerson->person->from_price_without_commission,
							);
						}

					}

					if( !$error ){
						array_push($exportedIds, $payment->id);
					}else{
						unset($arPayments[$key]);
					}
				}
			}

			return $arPayments;
		}

		function addDocumentToXML(&$xml, $arPayments){

			foreach ($arPayments as $code => $payment) {

				$document = $xml->addChild("Платеж");

				$document->addAttribute("ИД", $payment['ID']);
				$document->addAttribute("КодАгентства", $payment['AGENCY_ID']);
				$document->addAttribute("НомерСчета", $payment['NUMBER']);
				$document->addAttribute("Дата", $payment['DATE']);
				$document->addAttribute("НомерТранзакции", $payment['TRANSACTION']);
				$document->addAttribute("ТипОплаты", $payment['TYPE']);

				foreach ($payment['ORDERS'] as $orderID => $orderInfo) {
					$order = $document->addChild("Заказ");
					$order->addAttribute("Номер", $orderID);
					$order->addAttribute("Дата", $orderInfo['DATE']);
					foreach($orderInfo['PERSONS'] as $passengers){
						$passenger = $order->addChild("Пассажир");
						$passenger->addAttribute("ПассажирКод", $passengers['CODE']);
						$passenger->addAttribute("НомерСтроки", $passengers['NUMBER']);
						$passenger->addAttribute("Сумма", $passengers['SUM']);
					}
				}
			}
		}

		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Платежи/>');

		$arPayments = getArPayments($exportedIds);

		if (!empty($arPayments)) {
			addDocumentToXML($xml, $arPayments);
		}

		if( count($exportedIds) ){
			// Payment::model()->updateAll(array(
			// 	"export_date" => date("Y-m-d H:i:s")
			// ), "id IN (".implode(",", $exportedIds).")");
			
			Controller::returnXMLFile("payments", $xml->asXML());
		}
	}

	public function actionExportOrders(){
		function addDocumentToXML(&$xml, $order, $persons){
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

		$orders = Order::model()->findAll("export_date is NULL");

		foreach ($orders as $key => $order) {
			$order = addOrderToXML($xml, $order);
		}

		// Order::model()->updateAll(array(
		// 	"export_date" => date("Y-m-d H:i:s")
		// ), "export_date is NULL");

		Controller::returnXMLFile("orders", $xml->asXML());
		// file_put_contents("example.xml", $xml->asXML());
	}

	public function actionImportDictionaries($partial = false){
		// $filename = Yii::app()->basePath."/../1c_exchange/dictionaries.xml";
		$filename = Controller::readFileFromInput("dictionary");

		$xml = simplexml_load_file($filename);

		if( !$xml ){
			Debug::log("Пустой файл или не XML-файл", true);
			die();
		}

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
				Debug::log(print_r($model->getErrors(), true), true);
			}
		}
		Debug::log("ТочкиМаршрута импортированы ", true);

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
				Debug::log(print_r($model->getErrors(), true), true);
			}
		}
		Debug::log("Рейсы импортированы ", true);

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
				Debug::log(print_r($model->getErrors(), true), true);
			}
		}
		Debug::log("Партнеры импортированы ", true);

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
				Debug::log("Нет точки маршрута ".trim( $item->НачТочка )." – ".trim( $item->КонТочка ), true);
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
				Debug::log(print_r($model->getErrors(), true), true);
			}
		}
		Debug::log("СтоимостиПроезда импортированы ", true);

		// Импорт комиссий
		foreach( $xml->КомиссияАгентств->{"Элемент.КомиссияАгентств"} as $item ){
			$is_child = ( trim( $item->ТипПассажира ) == "Детский" )?1:0;
			$start_point_id = $points[ trim( $item->НачТочка ) ];
			$end_point_id = $points[ trim( $item->КонТочка ) ];
			$is_percent = ( trim( $item->Комиссия ) == "Комиссия %" )?1:0;
			$commission = intval( trim( $item->ЧислоКомиссии ) );

			if( empty($start_point_id) || empty($end_point_id) ){
				Debug::log("Нет точки маршрута ".trim( $item->НачТочка )." – ".trim( $item->КонТочка ), true);
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
				Debug::log(print_r($model->getErrors(), true), true);
			}
		}
		Debug::log("КомиссииАгентств импортированы ", true);
	}

	public function actionImportOrders($partial = false){
		// $filename = Yii::app()->basePath."/../1c_exchange/orders.xml";
		$filename = Controller::readFileFromInput("order");

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
