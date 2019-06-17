<?php

/**
 * This is the model class for table "note".
 *
 * The followings are the available columns in table "note":
 * @property string $id
 * @property string $property_id
 * @property string $date
 * @property string $text
 */
class Note extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "note";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("property_id, date, text", "required"),
			array("property_id", "length", "max" => 10),
			array("text", "length", "max" => 4096),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, property_id, date, text", "safe", "on" => "search"),
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
			"property" => array(self::BELONGS_TO, "Property", "property_id"),
			"files" => array(self::HAS_MANY, "File", "object_id", "on" => "object_type='2'"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			"id" => "ID",
			"property_id" => "Объект",
			"date" => "Дата",
			"text" => "Примечание",
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
		$criteria->addSearchCondition("property_id", $this->property_id);
		$criteria->addSearchCondition("date", $this->date);
		$criteria->addSearchCondition("text", $this->text);

		if( $count ){
			return Note::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "note/adminindex")
			));
		}
	}

	public function updateObj($attributes){
		foreach ($attributes as &$value) {
	    	$value = trim($value);
		}

		if( !$this->date ){
			$attributes["date"] = date("Y-m-d H:i:s", time());
		}else{
			$attributes["date"] = date("Y-m-d H:i:s", strtotime($this->date));
		}

		$this->attributes = $attributes;

		if($this->save()){
			return true;
		}else{
			print_r($this->getErrors());
			return false;
		}
	}

	public function afterFind(){
		parent::afterFind();

		$this->date = date("d.m.Y H:i:s", strtotime($this->date));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Note the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
