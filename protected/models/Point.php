<?php

/**
 * This is the model class for table "point".
 *
 * The followings are the available columns in table "point":
 * @property string $id
 * @property string $name
 * @property string $code_1c
 * @property string $active
 * @property string $is_airport
 * @property string $is_departure
 */
class Point extends CActiveRecord
{
	public $isDictionary = true;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "point";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("name, code_1c", "required"),
			array("name, active, is_airport, is_departure", "length", "max" => 128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, name, code_1c, active, is_airport, is_departure", "safe", "on" => "search"),
		);
	}

	public function scopes()
    {
        return array(
            "sorted" => array(
                "order" => "t.name ASC",
            ),
            "active" => array(
                "condition" => "t.active = '1'",
            ),
            "startAvailable" => array(
            	"with" => "pricesStart",
            	"condition" => "pricesStart.id IS NOT NULL"
            ),
            "endAvailable" => array(
            	"with" => "pricesEnd",
            	"condition" => "pricesEnd.id IS NOT NULL"
            ),
            "departureOnly" => array(
            	"condition" => "t.is_departure = '1'",
            )
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
			"ordersStart" => array(self::HAS_MANY, "Order", "start_point_id"),
			"ordersEnd" => array(self::HAS_MANY, "Order", "end_point_id"),
			"pricesStart" => array(self::HAS_MANY, "Price", "start_point_id"),
			"pricesEnd" => array(self::HAS_MANY, "Price", "end_point_id"),
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
				"name" => (object) array(
					"name" => "Наименование",
				),
				"code_1c" => (object) array(
					"name" => "Код 1С",
				),
				"is_airport" => (object) array(
					"name" => "Аэропорт",
					"type" => "bool"
				),
				"is_departure" => (object) array(
					"name" => "Отправная точка",
					"type" => "bool"
				),
				"active" => (object) array(
					"name" => "Активность",
					"type" => "bool"
				),
			);
		}else{
			return array(
				"id" => "ID",
				"name" => "Наименование",
				"code_1c" => "Код 1С",
				"is_airport" => "Аэропорт",
				"is_departure" => "Отправная точка",
				"active" => "Активность",
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
		$criteria->order = "name ASC";

		$criteria->addSearchCondition("id", $this->id);
		$criteria->addSearchCondition("name", $this->name);

		if( $count ){
			return Point::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "dictionary/list")
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
	 * @return Point the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
