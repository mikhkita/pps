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
 * @property integer $one_way_price
 * @property integer $commission
 * @property integer $cash
 * @property integer $to_status_id
 * @property integer $from_status_id
 * @property integer $passport
 * @property string $birthday
 * @property integer $pay_himself
 * @property string $code_1c
 * @property integer $number
 */
class Person extends CActiveRecord
{
	public $fio = NULL;
	public $age = NULL;
	public $direction = NULL;
	public $transfer = NULL;
	public $payment = NULL;
	public $price_without_commission = 0;

	public $ages = array( 0 => "Взрослый", 1 => "Детский" );
	public $directions = array( 1 => "В обе стороны", 2 => "Туда", 3 => "Обратно" );
	public $transfers = array( 1 => "На такси", 0 => "Самостоятельно" );
	public $payments = array( 0 => "Через турагентство", 1 => "Напрямую водителю" );
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
			array("name, last_name, order_id, phone, address", "required"),
			array("is_child, transfer_id, direction_id, price, one_way_price, commission, cash, to_status_id, from_status_id, pay_himself, number", "numerical", "integerOnly" => true),
			array("name, last_name, third_name", "length", "max" => 64),
			array("order_id", "length", "max" => 10),
			array("phone, code_1c, birthday", "length", "max" => 32),
			array("passport", "length", "max" => 14),
			array("comment, address", "length", "max" => 1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, name, last_name, third_name, order_id, is_child, phone, comment, address, transfer_id, direction_id, price, one_way_price, commission, cash, to_status_id, from_status_id, passport, birthday, pay_himself, code_1c, number", "safe", "on" => "search"),
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
			"back" => array(self::HAS_MANY, "BackPerson", "person_id"),
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
			"direction_id" => "Направление",
			"price" => "Стоимость",
			"one_way_price" => "Стоимость в одну сторону",
			"commission" => "Комиссия",
			"cash" => "Получено",
			"to_status_id" => "Статус поездки «туда»",
			"from_status_id" => "Статус поездки «обратно»",
			"status" => "Статус",
			"passport" => "Серия и номер паспорта",
			"birthday" => "День рождения",
			"pay_himself" => "Клиент оплачивает",
			"code_1c" => "Код 1С",
			"number" => "Номер строки (для 1С)",
			"fio" => "ФИО",
			"commission" => "Комиссия",
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
		$criteria->compare("one_way_price", $this->one_way_price);
		$criteria->compare("commission", $this->commission);
		$criteria->compare("cash", $this->cash);
		$criteria->compare("to_status_id", $this->to_status_id);
		$criteria->compare("from_status_id", $this->from_status_id);

		if( $count ){
			return Person::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "person/adminindex")
			));
		}
	}

	public function updateObj($attributes){
		foreach ($attributes as &$value) {
	    	$value = trim($value);
		}

		$attributes["birthday"] = ( empty($attributes["birthday"]) )?NULL:date("Y-m-d H:i:s", strtotime($attributes["birthday"]));

		$this->attributes = $attributes;

		if($this->save()){
			return true;
		}else{
			print_r($this->getErrors());
			return false;
		}
	}

	public function afterFind()
	{
		parent::afterFind();

		$this->fio = $this->last_name." ".$this->name.( ($this->third_name)?(" ".$this->third_name):"" );
		$this->age = $this->ages[ $this->is_child ];
		$this->direction = $this->directions[ $this->direction_id ];
		$this->transfer = $this->transfers[ $this->transfer_id ];
		$this->payment = $this->payments[ $this->pay_himself ];
		$this->price_without_commission = $this->price - $this->commission;

		if( !empty($this->birthday) ){
			$this->birthday = date("d.m.Y", strtotime($this->birthday));
		}
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
