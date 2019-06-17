<?php

/**
 * This is the model class for table "model_names".
 *
 * The followings are the available columns in table "model_names":
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $vin_name
 * @property string $rod_name
 * @property integer $admin_menu
 * @property integer $sort
 */
class ModelNames extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "model_names";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("code, name, vin_name, rod_name, menu_name", "required"),
			array("sort", "numerical", "integerOnly" => true),
			array("code, name, vin_name, rod_name", "length", "max" => 128),
			array("rule, menu_name", "length", "max" => 32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, code, name, vin_name, rod_name, menu_name, rule, sort", "safe", "on" => "search"),
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
			"code" => "Код",
			"name" => "Название раздела",
			"vin_name" => "Винительный падеж элемента",
			"rod_name" => "Родительный падеж элемента",
			"rule" => "Правило доступа",
			"sort" => "Сортировка",
			"parent" => "Родитель",
			"menu_name" => "Название в меню"
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare("id", $this->id);
		$criteria->compare("code", $this->code,true);
		$criteria->compare("name", $this->name,true);
		$criteria->compare("vin_name", $this->vin_name,true);
		$criteria->compare("rod_name", $this->rod_name,true);
		$criteria->compare("admin_menu", $this->admin_menu);
		$criteria->compare("sort", $this->sort);

		return new CActiveDataProvider($this, array(
			"criteria" => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ModelNames the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
