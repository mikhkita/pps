<?php

/**
 * This is the model class for table "orders".
 *
 * The followings are the available columns in table "orders":
 * @property string $id
 * @property string $to_date
 * @property string $from_date
 * @property string $to_time
 * @property string $from_time
 * @property string $create_date
 * @property string $export_date
 * @property string $start_point_id
 * @property string $end_point_id
 * @property string $to_flight_id
 * @property string $from_flight_id
 * @property string $comment
 * @property string $to_code_1c
 * @property string $from_code_1c
 * @property string $user_id
 * @property string $canceled
 * @property string $reason_1c
 */
class Order extends CActiveRecord
{
	public $title = NULL;
	public $price = NULL;
	public $cash = NULL;
	public $commission = NULL;
	public $agency_id = NULL;
	public $payment_status_id = NULL;

	public $paymentStatuses = array(  // Продублировано в Person для статуса оплаты у пассажира
		1 => "Не оплачено",
		2 => "Выставлен счет",
		3 => "Оплачено частично",
		4 => "Оплачено",
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "orders";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("start_point_id, end_point_id, user_id", "required", "message" => "Поле «{attribute}» не может быть пустым"),
			array("id, start_point_id, end_point_id", "length", "max" => 10, "tooLong" => "Поле «{attribute}» должно содержать не более 10 символов"),
			array("to_flight_id, from_flight_id, to_date, from_date, create_date, export_date, to_code_1c, from_code_1c", "length", "max" => 32, "tooLong" => "Поле «{attribute}» должно содержать не более 32 символов"),
			array("to_time, from_time, canceled", "length", "max" => 5, "tooLong" => "Поле «{attribute}» должно содержать не более 5 символов"),
			array("comment, reason_1c", "length", "max" => 1024, "tooLong" => "Поле «{attribute}» должно содержать не более 1024 символов"),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, to_date, from_date, to_time, from_time, create_date, export_date, start_point_id, end_point_id, to_flight_id, from_flight_id, comment, to_code_1c, from_code_1c, user_id, canceled, reason_1c", "safe", "on" => "search"),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			"startPoint" => array(self::BELONGS_TO, "Point", "start_point_id"),
			"endPoint" => array(self::BELONGS_TO, "Point", "end_point_id"),
			"flightTo" => array(self::BELONGS_TO, "Flight", "to_flight_id"),
			"flightFrom" => array(self::BELONGS_TO, "Flight", "from_flight_id"),
			"user" => array(self::BELONGS_TO, "User", "user_id"),
			"persons" => array(self::HAS_MANY, "Person", "order_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			"id" => "ID",
			"to_date" => "Дата/время выезда «туда»",
			"from_date" => "Дата/время выезда «обратно»",
			"create_date" => "Дата создания",
			"export_date" => "Дата выгрузки",
			"start_point_id" => "Город выезда/приезда",
			"end_point_id" => "Направление автобуса",
			"to_flight_id" => "Рейс вылета",
			"from_flight_id" => "Рейс прилета",
			"comment" => "Комментарий",
			"to_code_1c" => "Код 1С заявки «туда»",
			"from_code_1c" => "Код 1С заявки «обратно»",
			"user_id" => "Пользователь",
			"canceled" => "Отказ",
			"reason_1c" => "Причина отказа",
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search($pages, $count = false)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		if( !empty($this->agency_id) ){
			$criteria->with = "user";
			$criteria->addSearchCondition("user.agency_id", $this->agency_id);
		}

		$criteria->addSearchCondition("t.id", $this->id);
		$criteria->addSearchCondition("to_date", $this->to_date);
		$criteria->addSearchCondition("from_date", $this->from_date);
		$criteria->addSearchCondition("create_date", $this->create_date);
		$criteria->addSearchCondition("export_date", $this->export_date);
		$criteria->addSearchCondition("start_point_id", $this->start_point_id);
		$criteria->addSearchCondition("end_point_id", $this->end_point_id);
		$criteria->addSearchCondition("to_flight_id", $this->to_flight_id);
		$criteria->addSearchCondition("from_flight_id", $this->from_flight_id);
		$criteria->addSearchCondition("comment", $this->comment);
		$criteria->addSearchCondition("to_code_1c", $this->to_code_1c);
		$criteria->addSearchCondition("from_code_1c", $this->from_code_1c);
		$criteria->addSearchCondition("user_id", $this->user_id);

		$criteria->order = "t.id DESC";

		if( $count ){
			return Order::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "order/index")
			));
		}
	}

	public function updateObj($attributes, $persons){
		if( count($attributes) ){
			foreach ($attributes as &$value) {
		    	$value = trim($value);
			}
		}	

		$isNewRecord = $this->isNewRecord;

		if( $isNewRecord ){
			$attributes["user_id"] = Yii::app()->user->id;
			$attributes["from_flight_id"] = ( empty($attributes["from_flight_id"]) )?NULL:$attributes["from_flight_id"];
			$attributes["to_flight_id"] = ( empty($attributes["to_flight_id"]) )?NULL:$attributes["to_flight_id"];
		}

		if( count($attributes) ){
			$this->attributes = $attributes;
		}

		$errors = array();
		if($this->save()){
			if( count($persons) ){
				if( $isNewRecord ){
					$number = 0;
					foreach ($persons as $key => $person) {
						$number ++;

						foreach ($person as &$value) {
					    	$value = trim($value);
						}

						$model = new Person();

						$person["birthday"] = ( empty($person["birthday"]) )?NULL:date("Y-m-d H:i:s", strtotime($person["birthday"]));

						$model->attributes = $person;
						$model->order_id = $this->id;
						$model->number = $number;

						$model->to_status_id = ( $model->direction_id == 1 || $model->direction_id == 2 )?6:NULL;
						$model->from_status_id = ( $model->direction_id == 1 || $model->direction_id == 3 )?6:NULL;

						switch ($model->direction_id) {
							case 1:
								$model->to_price = intval($model->price) / 2;
								$model->from_price = intval($model->price) / 2;
								break;
							case 2:
								$model->to_price = intval($model->price);
								break;
							case 3:
								$model->from_price = intval($model->price);
								break;
						}
						
						if( !$model->save() ){
							array_push($errors, Controller::implodeErrors($model->getErrors()) );
						}
					}
				}else{
					foreach ($persons as $key => $person){
						if( $model = Person::model()->findByPk($key) ){
							$model->attributes = $person;
							if( !$model->save() ){
								array_push($errors, Controller::implodeErrors($model->getErrors()) );
							}
						}
					}
				}
			}
		}else{
			array_push($errors, Controller::implodeErrors($this->getErrors()) );
		}

		if( !count($errors) ){
			return (object) array(
				"result" => true
			);
		}else{
			return (object) array(
				"result" => false,
				"message" => implode("<br>", $errors)
			);
		}
	}

	public function getTitle(){
		if( $this->title === NULL ){
			$date = ( !empty($this->to_date) )?$this->to_date:$this->from_date;

			$this->title = $this->startPoint->name." – ".$this->endPoint->name;

			if( !empty($date) ){
				$this->title = $this->title.", ".Controller::getRusDate($date);
			}

			$this->title = $this->title." (".count($this->persons)." чел.)";
		}

		if( Yii::app()->user->checkAccess('root') ){
			return "#".$this->id." ".$this->title;
		}else{ 
			return $this->title;
		}
	}

	public function getTotals(){
		$this->price = 0;
		$this->cash = 0;
		$this->commission = 0;
		foreach ($this->persons as $key => $person) {
			$this->price = $this->price + $person->price;
			$this->cash = $this->cash + $person->cash;
			$this->commission = $this->commission + $person->commission;
		}
	}

	public function getTotalPrice(){
		if( $this->price === NULL ){
			$this->getTotals();
		}

		return $this->price;
	}

	public function getTotalCash(){
		if( $this->cash === NULL ){
			$this->getTotals();
		}

		return $this->cash;
	}

	public function getTotalCommission(){
		if( $this->commission === NULL ){
			$this->getTotals();
		}

		return $this->commission;
	}

	public function afterFind()
	{
		parent::afterFind();

		if( !empty($this->to_date) ){
			$this->to_date = date("d.m.Y", strtotime($this->to_date));

			if( $this->to_time ){
				$this->to_date = $this->to_date." ".$this->to_time;
			}
		}

		if( !empty($this->from_date) ){
			$this->from_date = date("d.m.Y", strtotime($this->from_date));

			if( $this->from_time ){
				$this->from_date = $this->from_date." ".$this->from_time;
			}
		}

		if( !empty($this->create_date) ){
			$this->create_date = date("d.m.Y H:i:s", strtotime($this->create_date));
		}

		if( !empty($this->export_date) ){
			$this->export_date = date("d.m.Y H:i:s", strtotime($this->export_date));
		}
	}

	public function getPaymentStatusId(){
		$statuses = array(
			1 => 0,
			2 => 0,
			3 => 0,
			4 => 0,
		);
		$count = count($this->persons);

		foreach ($this->persons as $key => $person) {
			$statuses[ $person->getPaymentStatusId() ] += 1;
		}

		if( $statuses[ 4 ] == $count ){
			$this->payment_status_id = 4;
		}else if( $statuses[ 3 ] > 0 || $statuses[ 4 ] > 0 ){
			$this->payment_status_id = 3;
		}else if( $statuses[ 2 ] == $count ){
			$this->payment_status_id = 2;
		}else{
			$this->payment_status_id = 1;
		}

		return $this->payment_status_id;
	}

	public function getPaymentStatus(){
		if( $this->payment_status_id == NULL ){
			$this->getPaymentStatusId();
		}

		return $this->paymentStatuses[ $this->payment_status_id ];
	}

	public function getPaymentStatusColor(){
		if( $this->payment_status_id == NULL ){
			$this->getPaymentStatusId();
		}

    	$colors = array(
    		1 => "grey",
    		2 => "blue",
    		3 => "orange",
    		4 => "green",
    	);
		return $colors[ $this->payment_status_id ];
    }

    protected function beforeDelete()
	{
		if(parent::beforeDelete() === false) {
			return false;
		}

		foreach ($this->persons as $key => $person) {
			$person->delete();
		}

		return true;
	}

	protected function beforeSave() {
        if (!parent::beforeSave()) {
            return false;
        }

        if( !empty($this->to_date) ){
        	$tmp = explode(" ", $this->to_date);
        	if( isset($tmp[1]) ){
        		$this->to_time = $tmp[1];
        	}
        }

        if( !empty($this->from_date) ){
        	$tmp = explode(" ", $this->from_date);
        	if( isset($tmp[1]) ){
        		$this->from_time = $tmp[1];
        	}
        }
        
        $this->to_date = ( empty($this->to_date) )?NULL:date("Y-m-d H:i:s", strtotime(str_replace(" ", " ", $this->to_date)));
		$this->from_date = ( empty($this->from_date) )?NULL:date("Y-m-d H:i:s", strtotime(str_replace(" ", " ", $this->from_date)));
		$this->create_date = ( empty($this->create_date) )?NULL:date("Y-m-d H:i:s", strtotime(str_replace(" ", " ", $this->create_date)));
		$this->export_date = ( empty($this->export_date) )?NULL:date("Y-m-d H:i:s", strtotime(str_replace(" ", " ", $this->export_date)));

        return true;
    }  

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
