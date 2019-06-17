<?php

/**
 * This is the model class for table "user_section".
 *
 * The followings are the available columns in table "user_section":
 * @property string $user_id
 * @property integer $section_id
 */
class UserSection extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "user_section";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("user_id, section_id", "required"),
			array("section_id", "numerical", "integerOnly" => true),
			array("user_id", "length", "max" => 10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("user_id, section_id", "safe", "on" => "search"),
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
			"user" => array(self::BELONGS_TO, "User", "user_id"),
			"section" => array(self::BELONGS_TO, "Section", "section_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			"user_id" => "User",
			"section_id" => "Section",
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

		$criteria->compare("user_id", $this->user_id);
		$criteria->compare("section_id", $this->section_id);

		if( $count ){
			return UserSection::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "userSection/adminindex")
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
	 * @return UserSection the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
