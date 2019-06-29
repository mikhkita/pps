<?php

/**
 * This is the model class for table "payment_person".
 *
 * The followings are the available columns in table "payment_person":
 * @property string $payment_id
 * @property string $person_id
 * @property integer $direction_id
 * @property string $sum
 */
class PaymentPerson extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "payment_person";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("payment_id, person_id, direction_id, sum", "required"),
			array("direction_id", "numerical", "integerOnly" => true),
			array("payment_id, person_id, sum", "length", "max" => 10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("payment_id, person_id, direction_id, sum", "safe", "on" => "search"),
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
			"person" => array(self::BELONGS_TO, "Person", "person_id"),
			"payment" => array(self::BELONGS_TO, "Payment", "payment_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			"payment_id" => "Платеж",
			"person_id" => "Пассажир",
			"direction_id" => "Направление",
			"sum" => "Сумма",
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

		$criteria->addSearchCondition("payment_id", $this->payment_id);
		$criteria->addSearchCondition("person_id", $this->person_id);
		$criteria->compare("direction_id", $this->direction_id);
		$criteria->addSearchCondition("sum", $this->sum);

		if( $count ){
			return PaymentPerson::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "paymentPerson/adminindex")
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
	 * @return PaymentPerson the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
