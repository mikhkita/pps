<?php

/**
 * This is the model class for table "Agency".
 *
 * The followings are the available columns in table "Agency":
 * @property integer $id
 * @property string $name
 * @property string $contractor
 * @property integer $sort
 */
class Agency extends CActiveRecord
{
	public $items = array();

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "Agency";
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
			array("code_1c", "numerical", "integerOnly" => true),
			array("name", "length", "max" => 64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, name, code_1c", "safe", "on" => "search"),
		);
	}

	public function scopes()
    {
        return array(
            "sorted" => array(
                "order" => "t.name ASC",
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
			"users" => array(self::HAS_MANY, "User", "agency_id"),
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
			"code_1c" => "Код 1С",
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
		$criteria->compare("code_1c", $this->code_1c);

		if( $count ){
			return Agency::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "Agency/adminindex")
			));
		}
	}

	public function updateObj($attributes){
		foreach ($attributes as &$value) {
	    	$value = trim($value);
		}

		$isNewRecord = $this->isNewRecord;
		$prev = clone $this;

		$this->attributes = $attributes;

		if($this->save()){
			if( $isNewRecord ){
				Log::add(14, $this->id, $this->name." (".$this->id.")", 1);
			}else{
				if( $diff = Controller::diff($prev, $this) )
					Log::add(14, $this->id, $this->name." (".$this->id.")", 2, $diff);
			}
			return true;
		}else{
			print_r($this->getErrors());
			return false;
		}
	}

	protected function beforeDelete()
	{
		if(parent::beforeDelete() === false) {
		  return false;
		}

		Log::add(14, $this->id, $this->name." (".$this->id.")", 4);

		return true;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Agency the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
