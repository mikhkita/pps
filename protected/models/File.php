<?php

/**
 * This is the model class for table "file".
 *
 * The followings are the available columns in table "file":
 * @property string $id
 * @property string $note_id
 * @property string $original
 * @property string $name
 */
class File extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "file";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("note_id, original, name", "required"),
			array("note_id", "length", "max" => 10),
			array("original", "length", "max" => 512),
			array("name", "length", "max" => 64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, note_id, original, name", "safe", "on" => "search"),
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
	public function attributeLabels()
	{
		return array(
			"id" => "ID",
			"note_id" => "Object",
			"original" => "Original",
			"name" => "Name",
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
		$criteria->compare("note_id", $this->note_id);
		$criteria->addSearchCondition("original", $this->original);
		$criteria->addSearchCondition("name", $this->name);

		if( $count ){
			return File::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "file/adminindex")
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
	 * @return File the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
