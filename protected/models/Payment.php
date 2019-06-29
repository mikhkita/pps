<?php

/**
 * This is the model class for table "payment".
 *
 * The followings are the available columns in table "payment":
 * @property string $id
 * @property string $user_id
 * @property string $number
 * @property string $date
 * @property integer $type_id
 */
class Payment extends CActiveRecord
{
	public $types = array(
		1 => "Онлайн оплата",
		2 => "Безналичный",
	);
	public $statuses = array(
		1 => "Новый",
		2 => "Не оплачен",
		3 => "Выставлен счет",
		4 => "Оплачен",
		5 => "Подтвержден",
	);
	public $type = NULL;
	public $status = NULL;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "payment";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("user_id, type_id", "required"),
			array("type_id, status_id", "numerical", "integerOnly" => true),
			array("user_id", "length", "max" => 10),
			array("number", "length", "max" => 32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, user_id, number, date, type_id, status_id", "safe", "on" => "search"),
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
			"user" => array(self::BELONGS_TO, "User", "user_id"),
			"persons" => array(self::HAS_MANY, "PaymentPerson", "payment_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			"id" => "ID",
			"user_id" => "Пользователь",
			"number" => "Счет",
			"date" => "Дата",
			"type_id" => "Тип платежа",
			"persons" => "Пассажиры",
			"sum" => "Сумма",
			"status_id" => "Статус",
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
		$criteria->addSearchCondition("user_id", $this->user_id);
		$criteria->addSearchCondition("number", $this->number);
		$criteria->addSearchCondition("date", $this->date);
		$criteria->compare("type_id", $this->type_id);

		if( $count ){
			return Payment::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "payment/adminindex")
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

	public function afterFind()
	{
		parent::afterFind();

		$this->date = date("d.m.Y H:i", strtotime($this->date));

		$this->type = $this->types[ $this->type_id ];
		$this->status = $this->statuses[ $this->status_id ];
	}

	public function getPersonsText(){
		$tmp = array();

		foreach ($this->persons as $key => $person) {
			array_push($tmp, $person->person->fio);
		}

		return "(".count($this->persons).") ".implode(", ", $tmp);
	}

	public function getTotalSum(){
		$sum = 0;

		foreach ($this->persons as $key => $person) {
			$sum = $sum + $person->sum;
		}

		return $sum;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Payment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}