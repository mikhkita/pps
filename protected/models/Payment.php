<?php

/**
 * This is the model class for table "payment".
 *
 * The followings are the available columns in table "payment":
 * @property integer $id
 * @property string $user_id
 * @property integer $number
 * @property integer $transaction
 * @property string $filename
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
		4 => "Оплачено",
		5 => "Подтвержден",
		6 => "Ошибка оплаты",
	);
	public $status = NULL;
	public $ext = "";

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
			array("type_id, status_id, number", "numerical", "integerOnly" => true),
			array("user_id", "length", "max" => 10),
			array("filename", "length", "max" => 1024),
			array("transaction", "length", "max" => 64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, user_id, number, date, type_id, status_id, filename, transaction", "safe", "on" => "search"),
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
			"type" => array(self::BELONGS_TO, "PaymentType", "type_id"),
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
			"number" => "Номер",
			"transaction" => "Номер транзакции",
			"filename" => "Ссылка на файл",
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
		$criteria->order = "id DESC";

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

		if( $this->filename ){
			$this->ext = substr(array_pop(explode(".", $this->filename)), 0, 3);
		}

		$this->status = $this->statuses[ $this->status_id ];
	}

	public function getPersonsText(){
		$tmp = array();

		foreach ($this->persons as $key => $person) {
			array_push($tmp, $person->person->fio);
		}

		return "<b>(".count($this->persons).")</b> ".implode(", ", $tmp);
	}

	public function getTotalSum(){
		$sum = 0;

		foreach ($this->persons as $key => $person) {
			$sum = $sum + $person->sum;
		}

		return $sum;
	}

	public function getTitle($onlyNumber = false){
		if( empty($this->number) && empty($this->transaction) ){
			$title = ( !$onlyNumber )?$this->type->create_title:"";
		}else{
			if( !$onlyNumber ){
				$title = $this->type->item_name." ";
			}
			switch ($this->type_id) {
				case 1:
					$title = $title."№".$this->transaction;
					break;
				case 2:
					$title = $title."№".$this->number." от ".Controller::getRusDate($this->date);
					break;
				
				default:
					$title = $title."№".$this->number;
					break;
			}
		}

		return $title;
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

        if( !empty($this->date) ){
        	$this->date = date("Y-m-d H:i:s", strtotime($this->date));
        }

        return true;
    }  

    public function getStatusColor(){
    	$statusClass = "blue";

		if( $this->status_id == 2 || $this->status_id == 3 ){
			$statusClass = "orange";
		}else if( $this->status_id == 4 || $this->status_id == 5 ){
			$statusClass = "green";
		} if( $this->status_id == 6 ){
			$statusClass = "red";
		}

		return $statusClass;
    }

    public function isEditable(){
  //   	switch ($payment->type_id) {
		// 	case 1:
		// 		$showButton = ($payment->status_id != 4 && $payment->status_id != 6);
		// 		break;
		// 	case 2:
		// 		$showButton = ($payment->status_id == 1);
		// 		break;
			
		// 	default:
		// 		$showButton = true;
		// 		break;
		// }


    	return ( empty($this->number) && empty($this->transaction) );
    }

	public function getNextBillNumber(){
		$model = Payment::model()->find(array(
			"condition" => "type_id = '2' AND date > '".date("Y")."-01-01 00:00:00' AND number IS NOT NULL",
			"order" => "number DESC"
		));

		if( $model ){
			return intval($model->number)+1;
		}else{
			return 1;
		}
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
