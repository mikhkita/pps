<?php

/**
 * This is the model class for table "note".
 *
 * The followings are the available columns in table "note":
 * @property string $id
 * @property integer $type_id
 * @property string $item_id
 * @property string $date
 * @property string $text
 * @property double $sum
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
			array("type_id, item_id, date, text", "required"),
			array("type_id", "numerical", "integerOnly" => true),
			array("sum", "numerical"),
			array("item_id", "length", "max" => 10),
			array("text", "length", "max" => 4096),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, type_id, item_id, date, text, sum", "safe", "on" => "search"),
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
			"pretension" => array(self::BELONGS_TO, "Pretension", "item_id"),
			"law" => array(self::BELONGS_TO, "Law", "item_id"),
			"execution" => array(self::BELONGS_TO, "Execution", "item_id"),
			"files" => array(self::HAS_MANY, "File", "note_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			"id" => "ID",
			"type_id" => "Тип",
			"item_id" => "ID элемента",
			"date" => "Дата",
			"text" => "Текст примечания",
			"sum" => "Сумма взыскания",
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
		$criteria->order = "date DESC";

		$criteria->compare("id", $this->id);
		$criteria->compare("type_id", $this->type_id);
		$criteria->compare("item_id", $this->item_id);
		$criteria->addSearchCondition("date", $this->date);
		$criteria->addSearchCondition("text", $this->text);
		$criteria->compare("sum", $this->sum);

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

		$isNewRecord = $this->isNewRecord;
		$prev = clone $this;

		if( !$this->date ){
			$attributes["date"] = date("Y-m-d H:i:s", time());
		}else{
			$attributes["date"] = date("Y-m-d H:i:s", strtotime($this->date));
		}

		$this->attributes = $attributes;

		if($this->save()){
			if( $isNewRecord ){
				Log::add("1".$this->type_id, $this->item_id, $this->getItemName()." (".$this->item_id.")", 7, "от ".$this->date);
			}else{
				if( $diff = Controller::diff($prev, $this) )
					Log::add("1".$this->type_id, $this->item_id, $this->getItemName()." (".$this->item_id.")", 8, "от ".$this->date."<br>".$diff);
			}
			return true;
		}else{
			print_r($this->getErrors());
			return false;
		}
	}

	public function getItemName(){
		switch ($this->type_id) {
			case 1:
				return $this->pretension->contractor;
				break;
			case 2:
				return $this->law->plf." - ".$this->law->dft;
				break;
			case 3:
				return $this->execution->debtor;
				break;
		}
	}

	public function afterFind(){
		parent::afterFind();

		$this->date = date("d.m.Y H:i:s", strtotime($this->date));
	}

	public function afterSave(){
		parent::afterSave();

		$this->date = date("d.m.Y H:i:s", strtotime($this->date));
	}

	protected function beforeDelete()
	{
		if(parent::beforeDelete() === false) {
			return false;
		}

		$ids = Controller::getIds($this->files);
		if( $ids ){
			Controller::removeFiles( $ids );
		}

		Log::add("1".$this->type_id, $this->item_id, $this->getItemName()." (".$this->item_id.")", 9, "от ".$this->date);

		return true;
	}

	public function getFilesString(){
		$out = array();
		foreach ($this->files as $key => $file){
			array_push($out, "<a href=\"http://".$_SERVER["HTTP_HOST"]."/".Yii::app()->params['docsFolder']."/".$file->id."/".$file->original."\" class=\"b-doc\" target=\"_blank\">".$file->original."</a>");
		}
		if( count($out) ){
			return implode(", ", $out);
		}else{
			return false;
		}
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
