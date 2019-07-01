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
				"actions" => array("importDictionaries", "exportOrder", "exportBack"),
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

		function getArBack(){
			
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
					$order = $document->addChild("Заявка");
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

		$arBack = getArBack();

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

				if( $person->pay_himself ){
					$personFields["СпособОплаты"] = "На руки водителю";
				}

				switch ($person->direction_id) {
					case 1:
						$personFields["Цена"] = number_format($person->price/2, 2, '.', '');

						array_push($personsTo, $personFields);
						array_push($personsFrom, $personFields);
						break;
					case 2:
						$personFields["Цена"] = number_format($person->price, 2, '.', '');
						array_push($personsTo, $personFields);
						break;
					case 3:
						$personFields["Цена"] = number_format($person->price, 2, '.', '');
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

		$order = Order::model()->findByPk(1);
		$order = addOrderToXML($xml, $order);

		$order = Order::model()->findByPk(2);
		$order = addOrderToXML($xml, $order);
		

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
}
