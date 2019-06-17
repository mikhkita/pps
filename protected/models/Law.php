<?php

/**
 * This is the model class for table "law".
 *
 * The followings are the available columns in table "law":
 * @property string $id
 * @property string $plf
 * @property string $dft
 * @property integer $is_material
 * @property string $text
 * @property double $debt_1
 * @property double $debt_2
 * @property double $debt_3
 * @property double $debt_4
 * @property double $debt_5
 * @property double $debt_6
 * @property string $court
 * @property string $number
 * @property string $manager_id
 */
class Law extends CActiveRecord
{
	public $last_note = NULL;
	public $recovered = 0;
	public $debt = 0;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "law";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("section_id, plf, dft, manager_id", "required"),
			array("section_id, is_material, archive", "numerical", "integerOnly" => true),
			array("debt_1, debt_2, debt_3, debt_4, debt_5, debt_6", "numerical"),
			array("plf, dft", "length", "max" => 256),
			array("text", "length", "max" => 1024),
			array("court", "length", "max" => 256),
			array("number", "length", "max" => 32),
			array("manager_id, pretension_id", "length", "max" => 10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("section_id, id, plf, archive, dft, is_material, text, debt_1, debt_2, debt_3, debt_4, debt_5, debt_6, court, number, manager_id, pretension_id", "safe", "on" => "search"),
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
			"pretension" => array(self::BELONGS_TO, "Pretension", "pretension_id"),
			"execution" => array(self::BELONGS_TO, "Execution", "law_id"),
			"section" => array(self::BELONGS_TO, "Section", "section_id"),
			"stakeholders" => array(self::HAS_MANY, "Stakeholder", "item_id", "on" => "type_id = '2'"),
			"notes" => array(self::HAS_MANY, "Note", "item_id", "on" => "type_id = '2'", "order" => "notes.date DESC"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			"id" => "ID",
			"plf" => "Истец",
			"dft" => "Ответчик",
			"is_material" => "Имущественный долг",
			"text" => "Цена иска",
			"debt" => "Остаток долга",
			"debt_1" => "Долг",
			"debt_2" => "Пеня",
			"debt_3" => "Гос. пошлина",
			"debt_4" => "Третейский сбор",
			"debt_5" => "Представительские",
			"debt_6" => "Иные расходы",
			"court" => "Суд",
			"number" => "Номер дела",
			"manager_id" => "Ответственный",
			"init_debt" => "Изначальная задолженность",
			"notes" => "Примечания",
			"stakeholders" => "Заинтересованные лица",
			"recovered" => "Взыскано",
			"archive" => "Архивировать",
			"pretension_id" => "Претензия",
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
		$criteria->addSearchCondition("plf", $this->plf);
		$criteria->addSearchCondition("dft", $this->dft);
		$criteria->compare("section_id", $this->section_id);
		$criteria->compare("is_material", $this->is_material);
		$criteria->addSearchCondition("text", $this->text);
		$criteria->compare("archive", $this->archive);
		$criteria->compare("debt_1", $this->debt_1);
		$criteria->compare("debt_2", $this->debt_2);
		$criteria->compare("debt_3", $this->debt_3);
		$criteria->compare("debt_4", $this->debt_4);
		$criteria->compare("debt_5", $this->debt_5);
		$criteria->compare("debt_6", $this->debt_6);
		$criteria->compare("pretension_id", $this->pretension_id);
		$criteria->addSearchCondition("court", $this->court);
		$criteria->addSearchCondition("number", $this->number);
		$criteria->compare("manager_id", $this->manager_id);

		if( $count ){
			return Law::model()->count($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				"criteria" => $criteria,
				"pagination" => array("pageSize" => $pages, "route" => "law/adminindex")
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
	}

	public function getStakeholdersString($field = "fio"){
		$out = array();
		foreach ($this->stakeholders as $key => $user) {
			array_push($out, $user->user->{$field});
		}
		return implode(", ", $out);
	}

	public function getDistinct($field){
		$criteria = new CDbCriteria;
		$criteria->distinct = true;
		$criteria->select = $field;

		$model = Law::model()->findAll($criteria);

        $arr = array();
        foreach ($model as $i => $item) 
            $arr[] = $item->{$field};

        return json_encode($arr);
    }

	public function updateObj($attributes){
		foreach ($attributes as &$value) {
	    	$value = trim($value);
		}

		$isNewRecord = $this->isNewRecord;
		$prev = clone $this;

		if( $isNewRecord && $this->pretension_id !== NULL ){
			Pretension::model()->updateByPk($this->pretension_id, array("archive" => 1));
		}

		$this->attributes = $attributes;

		if($this->save()){
			if( $isNewRecord ){
				Log::add(12, $this->id, $this->plf." - ".$this->dft." (".$this->id.")", 1);
			}else{
				if( $diff = Controller::diff($prev, Law::model()->with("manager", "stakeholders")->findByPk($this->id)) )
					Log::add(12, $this->id, $this->plf." - ".$this->dft." (".$this->id.")", 2, $diff);
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

		Log::add(12, $this->id, $this->plf." - ".$this->dft." (".$this->id.")", 4);

		return true;
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

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Law the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
