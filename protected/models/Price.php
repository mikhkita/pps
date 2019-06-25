<?php

/**
 * This is the model class for table "price".
 *
 * The followings are the available columns in table "price":
 * @property string $id
 * @property string $date
 * @property integer $is_child
 * @property string $start_point_id
 * @property string $end_point_id
 * @property string $one_way_price
 * @property string $total_price
 */
class Price extends CActiveRecord
{
	public $isDictionary = true;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "price";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("date, is_child, start_point_id, end_point_id, one_way_price, total_price", "required"),
			array("is_child", "numerical", "integerOnly" => true),
			array("start_point_id, end_point_id, one_way_price, total_price", "length", "max" => 10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, date, is_child, start_point_id, end_point_id, one_way_price, total_price", "safe", "on" => "search"),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels($viewLabels = false)
	{
		if( $viewLabels ){
			return array(
				"id" => (object) array(
					"name" => "ID",
					"width" => "30px"
				),
				"date" => (object) array(
					"name" => "Дата",
					"class" => "date"
				),
				"is_child" => (object) array(
					"name" => "Детский билет",
					"type" => "bool"
				),
				"start_point_id" => (object) array(
					"name" => "Начальная точка",
					"type" => "select",
					"model" => "Point",
					"relation" => "startPoint",
				),
				"end_point_id" => (object) array(
					"name" => "Конечная точка",
					"type" => "select",
					"model" => "Point",
					"relation" => "endPoint",
				),
				"one_way_price" => (object) array(
					"name" => "Цена в одну сторону",
					"class" => "numeric"
				),
				"total_price" => (object) array(
					"name" => "Цена в обе стороны",
					"class" => "numeric"
				),
			);
		}else{
			return array(
				"id" => "ID",
				"date" => "Дата",
				"is_child" => "Детский билет",
				"start_point_id" => "Начальная точка",
				"end_point_id" => "Конечная точка",
				"one_way_price" => "Цена в одну сторону",
				"total_price" => "Цена в обе стороны",
			);
		}
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
		$criteria->compare("is_child", $this->is_child);
		$criteria->addSearchCondition("start_point_id", $this->start_point_id);
		$criteria->addSearchCondition("end_point_id", $this->end_point_id);
		$criteria->addSearchCondition("one_way_price", $this->one_way_price);
		$criteria->addSearchCondition("total_price", $this->total_price);

		if( $count ){
			return Price::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "price/adminindex")
			));
		}
	}

	public function updateObj($attributes){
		foreach ($attributes as &$value) {
	    	$value = trim($value);
		}

		$attributes["date"] = date("Y-m-d H:i:s", strtotime($attributes["date"]));

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

		$this->date = date("d.m.Y", strtotime($this->date));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Price the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
