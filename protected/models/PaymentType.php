<?php

/**
 * This is the model class for table "payment_type".
 *
 * The followings are the available columns in table "payment_type":
 * @property integer $id
 * @property string $name
 * @property string $create_title
 * @property string $item_name
 * @property string $name_1c
 */
class PaymentType extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "payment_type";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("name", "required"),
			array("name", "length", "max" => 16),
			array("create_title", "length", "max" => 64),
			array("item_name, name_1c", "length", "max" => 32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, name, create_title, item_name, name_1c", "safe", "on" => "search"),
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
			"payments" => array(self::HAS_MANY, "Payment", "type_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			"id" => "ID",
			"name" => "Наименование",
			"create_title" => "Заголовок на странице создания",
			"item_name" => "Наименование платежа",
			"name_1c" => "Наименование для 1С",
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

		$criteria->compare("id", $this->id);
		$criteria->addSearchCondition("name", $this->name);
		$criteria->addSearchCondition("create_title", $this->create_title);

		if( $count ){
			return PaymentType::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "paymentType/adminindex")
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
	 * @return PaymentType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
