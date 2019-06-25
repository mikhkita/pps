<?php

/**
 * This is the model class for table "orders".
 *
 * The followings are the available columns in table "orders":
 * @property string $id
 * @property string $to_date
 * @property string $from_date
 * @property string $create_date
 * @property string $export_date
 * @property string $start_point_id
 * @property string $end_point_id
 * @property string $flight_id
 * @property string $comment
 * @property string $to_code_1c
 * @property string $from_code_1c
 * @property string $user_id
 */
class Order extends CActiveRecord
{
	public $title = NULL;
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
			array("start_point_id, end_point_id, user_id", "required"),
			array("id, start_point_id, end_point_id, to_code_1c, from_code_1c", "length", "max" => 10),
			array("flight_id", "length", "max" => 32),
			array("comment", "length", "max" => 1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, to_date, from_date, create_date, export_date, start_point_id, end_point_id, flight_id, comment, to_code_1c, from_code_1c, user_id", "safe", "on" => "search"),
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
			"flight" => array(self::BELONGS_TO, "Flight", "flight_id"),
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
			"start_point_id" => "Откуда",
			"end_point_id" => "Куда",
			"flight_id" => "Рейс",
			"comment" => "Комментарий",
			"to_code_1c" => "Код 1С заявки «туда»",
			"from_code_1c" => "Код 1С заявки «обратно»",
			"user_id" => "Пользователь",
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
		$criteria->addSearchCondition("to_date", $this->to_date);
		$criteria->addSearchCondition("from_date", $this->from_date);
		$criteria->addSearchCondition("create_date", $this->create_date);
		$criteria->addSearchCondition("export_date", $this->export_date);
		$criteria->addSearchCondition("start_point_id", $this->start_point_id);
		$criteria->addSearchCondition("end_point_id", $this->end_point_id);
		$criteria->addSearchCondition("flight_id", $this->flight_id);
		$criteria->addSearchCondition("comment", $this->comment);
		$criteria->addSearchCondition("to_code_1c", $this->to_code_1c);
		$criteria->addSearchCondition("from_code_1c", $this->from_code_1c);
		$criteria->addSearchCondition("user_id", $this->user_id);

		if( $count ){
			return Order::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "order/adminindex")
			));
		}
	}

	public function updateObj($attributes, $persons){
		foreach ($attributes as &$value) {
	    	$value = trim($value);
		}

		$attributes["user_id"] = Yii::app()->user->id;

		if( !empty($attributes["to_date"]) ){
			$attributes["to_date"] = date("Y-m-d H:i:s", strtotime($attributes["to_date"]));
		}

		if( !empty($attributes["from_date"]) ){
			$attributes["from_date"] = date("Y-m-d H:i:s", strtotime($attributes["from_date"]));
		}

		$this->attributes = $attributes;

		if($this->save()){
			if( count($persons) ){
				$number = 0;
				foreach ($persons as $key => $person) {
					$number ++;

					$model = new Person();
					$model->attributes = $person;
					$model->order_id = $this->id;
					$model->number = $number;

					if( !$model->save() ){
						print_r($model->getErrors());
						// die();
					}
				}
			}

			return true;
		}else{
			print_r($this->getErrors());
			return false;
		}
	}

	public function getTitle(){
		if( $this->title === NULL ){
			$date = ( !empty($this->to_date) )?$this->to_date:$this->from_date;

			$this->title = $this->startPoint->name." – ".$this->endPoint->name;

			if( !empty($date) ){
				$this->title = $this->title." ".Controller::getRusDate($date);
			}

			$this->title = $this->title." (".count($this->persons)." чел.)";
		}

		return $this->title;
	}

	public function afterFind()
	{
		parent::afterFind();

		if( !empty($this->to_date) ){
			$this->to_date = date("d.m.Y H:i", strtotime($this->to_date));
		}

		if( !empty($this->from_date) ){
			$this->from_date = date("d.m.Y H:i", strtotime($this->from_date));
		}

		if( !empty($this->create_date) ){
			$this->create_date = date("d.m.Y H:i:s", strtotime($this->create_date));
		}

		if( !empty($this->export_date) ){
			$this->export_date = date("d.m.Y H:i:s", strtotime($this->export_date));
		}
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
