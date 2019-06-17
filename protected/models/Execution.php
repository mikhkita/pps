<?php

/**
 * This is the model class for table "execution".
 *
 * The followings are the available columns in table "execution":
 * @property string $id
 * @property integer $section_id
 * @property string $debtor
 * @property integer $is_material
 * @property string $text
 * @property integer $debt_1
 * @property integer $debt_2
 * @property integer $debt_3
 * @property integer $debt_4
 * @property integer $debt_5
 * @property integer $debt_6
 * @property string $start_date
 * @property string $end_date
 * @property string $manager_id
 * @property integer $state_id
 */
class Execution extends CActiveRecord
{
	public $last_note = NULL;
	public $recovered = 0;
	public $debt = 0;
	public $is_ending = false;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "execution";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("section_id, debtor, start_date, manager_id", "required"),
			array("section_id, is_material, notified, state_id, highlight", "numerical", "integerOnly" => true),
			array("debt_1, debt_2, debt_3, debt_4, debt_5, debt_6", "numerical"),
			array("debtor", "length", "max" => 256),
			array("text", "length", "max" => 1024),
			array("manager_id, law_id", "length", "max" => 10),
			array("end_date", "safe"),
			array("comment", "length", "max" => 4096),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, section_id, debtor, is_material, notified, text, comment, debt_1, debt_2, debt_3, debt_4, debt_5, debt_6, start_date, end_date, manager_id, state_id, law_id, highlight", "safe", "on" => "search"),
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
			"manager" => array(self::BELONGS_TO, "User", "manager_id"),
			"law" => array(self::BELONGS_TO, "Law", "law_id"),
			"section" => array(self::BELONGS_TO, "Section", "section_id"),
			"stakeholders" => array(self::HAS_MANY, "Stakeholder", "item_id", "on" => "type_id = '3'"),
			"notes" => array(self::HAS_MANY, "Note", "item_id", "on" => "type_id = '3'", "order" => "notes.date DESC"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			"id" => "ID",
			"section_id" => "Section",
			"debtor" => "Наименование должника",
			"is_material" => "Имущественный долг",
			"text" => "Цена иска",
			"debt" => "Остаток долга",
			"debt_1" => "Долг",
			"debt_2" => "Пеня",
			"debt_3" => "Гос. пошлина",
			"debt_4" => "Третейский сбор",
			"debt_5" => "Представительские",
			"debt_6" => "Иные расходы",
			"start_date" => "Дата возбуждения",
			"end_date" => "Дата прекращения",
			"manager_id" => "Ответственный",
			"state_id" => "Состояние",
			"init_debt" => "Изначальная задолженность",
			"notes" => "Примечания",
			"stakeholders" => "Заинтересованные лица",
			"recovered" => "Взыскано",
			"law_id" => "Судебное дело",
			"pretension_id" => "Претензия",
			"highlight" => "Подсвечивать",
			"comment" => "Комментарий",
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
		$criteria->compare("section_id", $this->section_id);
		$criteria->addSearchCondition("debtor", $this->debtor);
		$criteria->compare("is_material", $this->is_material);
		$criteria->addSearchCondition("text", $this->text);
		$criteria->compare("debt_1", $this->debt_1);
		$criteria->compare("debt_2", $this->debt_2);
		$criteria->compare("debt_3", $this->debt_3);
		$criteria->compare("debt_4", $this->debt_4);
		$criteria->compare("debt_5", $this->debt_5);
		$criteria->compare("debt_6", $this->debt_6);
		$criteria->compare("law_id", $this->law_id);
		$criteria->addSearchCondition("start_date", $this->start_date);
		$criteria->addSearchCondition("end_date", $this->end_date);
		$criteria->addSearchCondition("manager_id", $this->manager_id);
		$criteria->compare("state_id", $this->state_id);

		if( $count ){
			return Execution::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "execution/adminindex")
			));
		}
	}

	public function getBySections($model){
		$sections = Controller::getAssoc(Section::model()->with(array("users" => array("condition" => "users.user_id = '".Yii::app()->user->id."'")))->findAll(""), "id");

		foreach ($model as $key => $item) {
			array_push($sections[$item->section_id]->items, $item);
		}

		return $sections;
	}

	public function afterFind()
	{
		parent::afterFind();

		$this->last_note = $this->notes[0];

		foreach ($this->notes as $key => $note) {
			if( $note->sum ){
				$this->recovered += $note->sum;
			}
		}
		if( $this->is_material ){
			$this->debt = $this->debt_1 + $this->debt_2 + $this->debt_3 + $this->debt_4 + $this->debt_5 + $this->debt_6;
		}

		$this->start_date = date("d.m.Y", strtotime($this->start_date));

		$this->is_ending = ( $this->state_id == 2 && $this->end_date && strtotime("+11 months", strtotime($this->end_date)) < time() );

		if( $this->end_date )
			$this->end_date = date("d.m.Y", strtotime($this->end_date));
	}

	public function beforeSave() {
		parent::beforeSave();
		
		$this->start_date = date("Y-m-d H:i:s", strtotime($this->start_date));

		if( isset($this->end_date) && $this->end_date != "" ){
			$this->end_date = date("Y-m-d H:i:s", strtotime($this->end_date));
		}else{
			$this->end_date = NULL;
		}

		return true;
	}

	public function afterSave()
	{
		parent::afterSave();

		$this->start_date = date("d.m.Y", strtotime($this->start_date));

		if( $this->end_date )
			$this->end_date = date("d.m.Y", strtotime($this->end_date));
	}

	public function getTooltip(){
		$out = array();
		$labels = Law::attributeLabels();

		for( $i = 1; $i <= 6; $i++ ){
			$key = "debt_".$i;
			if( $this[$key] ){
				array_push($out, $labels[$key].": ".number_format( $this[$key], 2, '.', '&nbsp;' )." руб.");
			}
		}

		array_push($out, "<b>".$labels["recovered"].": ".number_format( $this->recovered, 2, '.', '&nbsp;' )." руб.</b>");

		return implode("<br>", $out);
	}

	public function getStakeholdersString($field = "fio"){
		$out = array();
		foreach ($this->stakeholders as $key => $user) {
			array_push($out, $user->user->{$field});
		}
		return implode(", ", $out);
	}

	public function updateObj($attributes){
		foreach ($attributes as &$value) {
	    	$value = trim($value);
		}

		$isNewRecord = $this->isNewRecord;
		$prev = clone $this;

		if( $isNewRecord && $this->law_id !== NULL ){
			Law::model()->updateByPk($this->law_id, array("archive" => 1));
		}

		$this->attributes = $attributes;

		if($this->save()){
			if( $isNewRecord ){
				Log::add(13, $this->id, $this->debtor." (".$this->id.")", 1);
			}else{
				if( $diff = Controller::diff($prev, Execution::model()->with("manager", "stakeholders")->findByPk($this->id)) )
					Log::add(13, $this->id, $this->debtor." (".$this->id.")", 2, $diff);
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

		Log::add(13, $this->id, $this->debtor." (".$this->id.")", 4);

		return true;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Execution the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
