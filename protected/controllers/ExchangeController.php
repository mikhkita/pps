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
				"actions" => array("adminImportDictionaries"),
				"users" => array("*"),
			),
			array("deny",
				"users" => array("*"),
			),
		);
	}

	public function actionAdminImportDictionaries($partial = false){
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
