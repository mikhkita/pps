<?php

/**
 * This is the model class for table "log".
 *
 * The followings are the available columns in table "log":
 * @property string $id
 * @property string $date
 * @property integer $action_id
 * @property string $data
 * @property string $user_id
 * @property string $model_id
 */
class Log extends CActiveRecord
{
	public $date_from;
	public $date_to;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "log";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("action_id, user_id, model_id, item_id", "required"),
			array("action_id, model_id, item_id", "numerical", "integerOnly" => true),
			array("user_id", "length", "max" => 10),
			array("item", "length", "max" => 64),
			array("data", "safe"),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, date, action_id, data, user_id, item, model_id, item_id", "safe", "on" => "search"),
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
			"action" => array(self::BELONGS_TO, "Action", "action_id"),
			"model" => array(self::BELONGS_TO, "ModelNames", "model_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			"id" => "ID",
			"date" => "Дата",
			"action_id" => "Действие",
			"model_id" => "Сущность",
			"data" => "Примечание",
			"user_id" => "Пользователь",
			"item_id" => "ID элемента",
			"item" => "Элемент",
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
		$criteria->order = "date DESC, id DESC";

		if( $this->date_from != NULL && $this->date_from != "__.__.____" ){
			$criteria->addCondition("date >= '".date("Y-m-d H:i:s", strtotime($this->date_from))."'");
		}
		if( $this->date_to != NULL && $this->date_to != "__.__.____" ){
			$criteria->addCondition("date <= '".date("Y-m-d H:i:s", strtotime($this->date_to)+24*60*60-1)."'");
		}

		$criteria->compare("id", $this->id);
		$criteria->addSearchCondition("date", $this->date);
		$criteria->compare("action_id", $this->action_id);
		$criteria->addSearchCondition("data", $this->data);
		$criteria->compare("user_id", $this->user_id);
		$criteria->compare("model_id", $this->model_id);
		$criteria->compare("item_id", $this->item_id);
		$criteria->addSearchCondition("item", $this->item);

		if( $count ){
			return Log::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "log/adminindex")
			));
		}
	}

	public function add($model_id, $item_id, $item = "", $action_id, $data = ""){
		$new = new Log();
		$new->model_id = $model_id;
		$new->item_id = $item_id;
		$new->item = mb_substr($item, 0, 63, "UTF-8");
		$new->action_id = $action_id;
		$new->data = $data;
		$new->user_id = Yii::app()->user->id;
		if( !$new->save() ){
			echo "string";
			print_r($new->getErrors());
			die();
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

		$this->date = date("d.m.Y H:i:s", strtotime($this->date));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Log the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
