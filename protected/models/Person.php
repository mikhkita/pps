<?php

/**
 * This is the model class for table "person".
 *
 * The followings are the available columns in table "person":
 * @property string $id
 * @property string $last_name
 * @property string $name
 * @property string $third_name
 * @property string $order_id
 * @property integer $is_child
 * @property string $phone
 * @property string $comment
 * @property string $address
 * @property integer $transfer_id
 * @property integer $direction_id
 * @property integer $price
 * @property integer $to_price
 * @property integer $from_price
 * @property integer $commission
 * @property integer $cash
 * @property integer $to_status_id
 * @property integer $from_status_id
 * @property integer $passport
 * @property string $birthday
 * @property integer $payment_type_id
 * @property string $code_1c
 * @property integer $number
 */
class Person extends CActiveRecord
{
	public $fio = NULL;
	public $age = NULL;
	public $direction = NULL;
	public $transfer = NULL;
	public $paymentType = NULL;
	public $price_without_commission = 0;
	public $to_price_without_commission = 0;
	public $from_price_without_commission = 0;
	public $to_status = NULL;
	public $from_status = NULL;
	public $payment_status_id = NULL;

	public $ages = array( 
		0 => "Взрослый",
		1 => "Детский" 
	);
	public $directions = array( 
		1 => "В обе стороны",
		2 => "Туда",
		3 => "Обратно"
	);
	public $transfers = array( 
		1 => "На такси",
		0 => "Самостоятельно"
	);
	public $paymentTypes = array(  // Продублировано в Agency для способа оплаты по умолчанию
		1 => "Оплата по карте",
		2 => "Безналичный", 
		3 => "Наличный",
		4 => "На руки водителю",
	);
	public $statuses = array(
		1 => "В работе",
		2 => "Оформлен",
		3 => "Бронь",
		4 => "Исполнен",
		5 => "Отменен",
		6 => "В ожидании",
	);
	public $paymentStatuses = array(  // Продублировано в Order для статуса оплаты у заказа
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
		return "person";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("name, order_id, phone, address", "required"),
			array("is_child, transfer_id, direction_id, price, to_price, from_price, commission, cash, to_status_id, from_status_id, payment_type_id, number", "numerical", "integerOnly" => true),
			array("name, last_name, third_name", "length", "max" => 64),
			array("order_id", "length", "max" => 10),
			array("phone, code_1c, birthday", "length", "max" => 32),
			array("passport", "length", "max" => 14),
			array("comment, address", "length", "max" => 1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, name, last_name, third_name, order_id, is_child, phone, comment, address, transfer_id, direction_id, price, to_price, from_price, commission, cash, to_status_id, from_status_id, passport, birthday, payment_type_id, code_1c, number", "safe", "on" => "search"),
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
			"order" => array(self::BELONGS_TO, "Order", "order_id"),
			"backs" => array(self::HAS_MANY, "BackPerson", "person_id"),
			"payments" => array(self::HAS_MANY, "PaymentPerson", "person_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			"id" => "ID",
			"last_name" => "Фамилия",
			"name" => "Имя",
			"third_name" => "Отчество",
			"order_id" => "Заказ",
			"is_child" => "Билет",
			"phone" => "Телефон",
			"comment" => "Комментарий",
			"address" => "Адрес",
			"transfer_id" => "Добирается до посадки",
			"direction_id" => "Направление пассажира",
			"price" => "Стоимость",
			"to_price" => "Стоимость туда (для 1С)",
			"from_price" => "Стоимость обратно (для 1С)",
			"commission" => "Комиссия",
			"cash" => "Получено",
			"to_status_id" => "Статус поездки «туда»",
			"from_status_id" => "Статус поездки «обратно»",
			"status" => "Статус",
			"passport" => "Серия и номер паспорта",
			"birthday" => "День рождения",
			"payment_type_id" => "Способ оплаты",
			"code_1c" => "Код 1С",
			"number" => "Номер строки (для 1С)",
			"fio" => "ФИО",
			"payment_status" => "Оплата",
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

		$criteria->addSearchCondition("id", $this->id);
		$criteria->addSearchCondition("name", $this->name);
		$criteria->addSearchCondition("last_name", $this->last_name);
		$criteria->addSearchCondition("third_name", $this->third_name);
		$criteria->addSearchCondition("order_id", $this->order_id);
		$criteria->compare("is_child", $this->is_child);
		$criteria->addSearchCondition("phone", $this->phone);
		$criteria->addSearchCondition("comment", $this->comment);
		$criteria->addSearchCondition("address", $this->address);
		$criteria->addSearchCondition("passport", $this->passport);
		$criteria->compare("transfer_id", $this->transfer_id);
		$criteria->compare("direction_id", $this->direction_id);
		$criteria->compare("price", $this->price);
		$criteria->compare("commission", $this->commission);
		$criteria->compare("cash", $this->cash);
		$criteria->compare("to_status_id", $this->to_status_id);
		$criteria->compare("from_status_id", $this->from_status_id);

		if( $count ){
			return Person::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "person/index")
			));
		}
	}

	public function updateObj($attributes){
		foreach ($attributes as &$value) {
	    	$value = trim($value);
		}

		$this->attributes = $attributes;

		if($this->save()){
			return true;
		}else{
			print_r($this->getErrors());
			return false;
		}
	}

	public function getStatusColor($field){
    	$colors = array(
    		1 => "blue",
    		2 => "orange",
    		3 => "blue",
    		4 => "grey",
    		5 => "red",
    	);
		return $colors[ $field ];
    }

	public function afterFind()
	{
		parent::afterFind();

		$this->fio = $this->last_name." ".$this->name.( ($this->third_name)?(" ".$this->third_name):"" );
		$this->age = $this->ages[ $this->is_child ];
		$this->direction = $this->directions[ $this->direction_id ];
		$this->transfer = $this->transfers[ $this->transfer_id ];
		$this->paymentType = $this->paymentTypes[ $this->payment_type_id ];
		$this->price_without_commission = $this->price - $this->commission;

		if( !empty($this->to_price) ){
			$this->to_price_without_commission = $this->to_price - ( ($this->direction_id == 1)?($this->commission/2):$this->commission );
		}
		if( !empty($this->from_price) ){
			$this->from_price_without_commission = $this->from_price - ( ($this->direction_id == 1)?($this->commission/2):$this->commission );
		}

		if( !empty($this->to_status_id) ){
			$this->to_status = $this->statuses[ $this->to_status_id ];
		}

		if( !empty($this->from_status_id) ){
			$this->from_status = $this->statuses[ $this->from_status_id ];
		}

		if( !empty($this->birthday) ){
			$this->birthday = date("d.m.Y", strtotime($this->birthday));
		}
	}

	public function getPaymentStatusId(){
		$payed = 0;
		$isBill = false;

		foreach ($this->payments as $key => $paymentPerson) {
			$payment = $paymentPerson->payment;

			if( $payment->status_id == 4 || $payment->status_id == 5 ){
				$payed = $payed + $paymentPerson->sum;
			}

			if( $payment->status_id == 3 ){
				$isBill = true;
			}
		}

		if( $payed > 0 ){
			if( $payed == $this->price - $this->commission ){
				$this->payment_status_id = 4;
			}else{
				$this->payment_status_id = 3;
			}
		}else{
			if( $isBill ){
				$this->payment_status_id = 2;
			}else{
				$this->payment_status_id = 1;
				
			}
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

		foreach ($this->backs as $key => $back) {
			$back->delete();
		}

		foreach ($this->payments as $key => $payment) {
			$payment->delete();
		}

		return true;
	}


    protected function beforeSave() {
        if (!parent::beforeSave()) {
            return false;
        }

        $this->birthday = ( empty($this->birthday) )?NULL:date("Y-m-d H:i:s", strtotime(str_replace(" ", " ", $this->birthday)));
        $this->price = intval($this->to_price) + intval($this->from_price);

        return true;
    }  

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Person the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
