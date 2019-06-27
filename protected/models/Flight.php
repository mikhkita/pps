<?php

/**
 * This is the model class for table "flight".
 *
 * The followings are the available columns in table "flight":
 * @property string $id
 * @property string $name
 * @property integer $active
 * @property string $code_1c
 */
class Flight extends CActiveRecord
{
	public $isDictionary = true;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "flight";
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
			array("active", "numerical", "integerOnly" => true),
			array("name", "length", "max" => 256),
			array("code_1c", "length", "max" => 32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, name, active, code_1c", "safe", "on" => "search"),
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
					"name" => "Наименование"
				),
				"code_1c" => (object) array(
					"name" => "Код 1С",
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
				"active" => "Активность",
				"code_1c" => "Код 1С",
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
		$criteria->compare("active", $this->active);
		$criteria->addSearchCondition("code_1c", $this->code_1c);

		if( $count ){
			return Flight::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "dictionary/adminlist")
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
	 * @return Flight the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
