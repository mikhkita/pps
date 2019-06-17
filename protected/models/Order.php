<?php

/**
 * This is the model class for table "orders".
 *
 * The followings are the available columns in table "orders":
 * @property string $id
 * @property string $date
 * @property string $start_point_id
 * @property string $end_point_id
 * @property string $flight
 * @property string $comment
 * @property string $to_id
 * @property string $from_id
 */
class Order extends CActiveRecord
{
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
			array("date, start_point_id, end_point_id, flight", "required"),
			array("id, start_point_id, end_point_id, to_id, from_id", "length", "max" => 10),
			array("flight", "length", "max" => 32),
			array("comment", "length", "max" => 1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, date, start_point_id, end_point_id, flight, comment, to_id, from_id", "safe", "on" => "search"),
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
			"date" => "Дата поездки",
			"start_point_id" => "Город выезда",
			"end_point_id" => "Направление",
			"flight" => "Рейс",
			"comment" => "Комментарий",
			"to_id" => "Код 1С заявки «туда»",
			"from_id" => "Код 1С заявки «обратно»",
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
		$criteria->addSearchCondition("date", $this->date);
		$criteria->addSearchCondition("start_point_id", $this->start_point_id);
		$criteria->addSearchCondition("end_point_id", $this->end_point_id);
		$criteria->addSearchCondition("flight", $this->flight);
		$criteria->addSearchCondition("comment", $this->comment);
		$criteria->addSearchCondition("to_id", $this->to_id);
		$criteria->addSearchCondition("from_id", $this->from_id);

		if( $count ){
			return Order::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "order/adminindex")
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
