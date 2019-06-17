<?php

/**
 * This is the model class for table "pretension".
 *
 * The followings are the available columns in table "pretension":
 * @property string $id
 * @property integer $section_id
 * @property integer $dept_id
 * @property double $debt
 * @property string $send_date
 * @property integer $days
 * @property string $manager_id
 */
class Pretension extends CActiveRecord
{
	public $last_note = NULL;
	public $end_date = "";
	public $recovered = 0;
	public $is_expired = false;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "pretension";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("section_id, contractor, debt, send_date, days, manager_id", "required"),
			array("section_id, dept_id, days, archive, notified, highlight", "numerical", "integerOnly" => true),
			array("debt", "numerical"),
			array("contractor", "length", "max" => 256),
			array("manager_id", "length", "max" => 10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, section_id, dept_id, contractor, debt, send_date, days, archive, notified, manager_id, highlight", "safe", "on" => "search"),
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
			"section" => array(self::BELONGS_TO, "Section", "section_id"),
			"law" => array(self::BELONGS_TO, "Law", "pretension_id"),
			"stakeholders" => array(self::HAS_MANY, "Stakeholder", "item_id", "on" => "type_id = '1'"),
			"notes" => array(self::HAS_MANY, "Note", "item_id", "on" => "type_id = '1'", "order" => "notes.date DESC"),
			"dept" => array(self::BELONGS_TO, "Dept", "dept_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			"id" => "ID",
			"section_id" => "Группа бизнеса",
			"dept_id" => "Подразделение",
			"contractor" => "Наименование контрагента",
			"debt" => "Остаток долга",
			"init_debt" => "Общая задолженность",
			"recovered" => "Взыскано",
			"send_date" => "Дата отправки",
			"stakeholders" => "Заинтересованные лица",
			"days" => "Срок ответа",
			"manager_id" => "Ответственный",
			"notes" => "Примечания",
			"archive" => "Архивировать",
			"highlight" => "Подсвечивать",
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

		$criteria = new CDbCriteria;
		$criteria->with = array("section", "notes", "manager");
		$criteria->order = "section.sort ASC, t.id ASC";

		$criteria->compare("id", $this->id);
		$criteria->compare("section_id", $this->section_id);
		$criteria->addSearchCondition("t.contractor", $this->contractor);
		$criteria->compare("dept_id", $this->dept_id);
		$criteria->compare("archive", $this->archive);
		$criteria->compare("debt", $this->debt);
		$criteria->addSearchCondition("send_date", $this->send_date);
		$criteria->compare("days", $this->days);
		$criteria->compare("manager_id", $this->manager_id);

		if( $count ){
			return Pretension::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "pretension/adminindex")
			));
		}
	}
	
	public function afterFind()
	{
		parent::afterFind();

		$this->send_date = date("d.m.Y", strtotime($this->send_date));
		$this->last_note = $this->notes[0];
		$this->end_date = Controller::addDays($this->send_date, intval($this->days));
		$this->is_expired = ( strtotime($this->end_date) < time() );

		foreach ($this->notes as $key => $note) {
			if( $note->sum ){
				$this->recovered += $note->sum;
			}
		}
	}

	public function afterSave()
	{
		parent::afterSave();

		$this->send_date = date("d.m.Y", strtotime($this->send_date));
	}

	public function getBySections($model){
		$sections = Controller::getAssoc(Section::model()->with(array("users" => array("condition" => "users.user_id = '".Yii::app()->user->id."'")))->findAll(""), "id");

		foreach ($model as $key => $item) {
			array_push($sections[$item->section_id]->items, $item);
		}

		return $sections;
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
				Log::add(11, $this->id, $this->contractor." (".$this->id.")", 1);
			}else{
				if( $diff = Controller::diff($prev, Pretension::model()->with("manager", "stakeholders")->findByPk($this->id)) )
					Log::add(11, $this->id, $this->contractor." (".$this->id.")", 2, $diff);
			}
			return true;
		}else{
			print_r($this->getErrors());
			return false;
		}
	}

	public function beforeSave() {
		parent::beforeSave();
		
		$this->send_date = date("Y-m-d H:i:s", strtotime($this->send_date));

		return true;
	}

	protected function beforeDelete()
	{
		if(parent::beforeDelete() === false) {
		  return false;
		}

		Log::add(11, $this->id, $this->contractor." (".$this->id.")", 4);

		return true;
	}

	public function getStakeholdersString($field = "fio"){
		$out = array();
		foreach ($this->stakeholders as $key => $user) {
			array_push($out, $user->user->{$field});
		}
		return implode(", ", $out);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pretension the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
