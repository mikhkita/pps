<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table "user":
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $name
 * @property string $email
 */
class User extends CActiveRecord
{
	public $fio = "";

	const ROLE_USER = "user";
	const ROLE_ADMIN = "admin";
	const ROLE_ROOT = "root";

	const STATE_ACTIVE = 1;
	const STATE_DISABLED = 0;

	public $prevPass = null;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "user";
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
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("login, password, name", "required"),
			array("active, agency_id", "numerical", "integerOnly"=>true),
			array("login, password, email", "length", "max"=>128),
			array("name", "length", "max"=>255),
			array("token", "length", "max"=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array("id, login, password, name, email, active, token, agency_id", "safe", "on"=>"search"),
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
			"orders" => array(self::HAS_MANY, "Order", "user_id"),
			"payments" => array(self::HAS_MANY, "Payment", "user_id"),
			"roles" => array(self::HAS_MANY, "UserRole", "user_id"),
			"agency" => array(self::BELONGS_TO, "Agency", "agency_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			"id" => "ID",
			"login" => "Логин",
			"password" => "Пароль",
			"name" => "Имя",
			"fio" => "ФИО",
			"email" => "E-mail",
			"roles" => "Роли",
			"active" => "Активность",
			"agency_id" => "Агентство",
			"token" => "Токен",
		);
	}

	public function beforeSave() {
		parent::beforeSave();
		$this->password = ( $this->prevPass == $this->password ) ? $this->password : md5($this->password."eduplan");

		if( !$this->login || !$this->password ){
	        return false;
		}

		if( !Yii::app()->user->checkAccess("updateUser") )
			throw new CHttpException(403,"Доступ запрещен");

		return true;
	}

	public function afterSave() {
		parent::afterSave();

		if( $this->isNewRecord ){
			$auth = Yii::app()->authManager;
			$auth->assign("readAll", $this->id);
			$auth->save();
		}
		
		return true;
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

		$criteria=new CDbCriteria;

		$criteria->compare("id", $this->id);
		$criteria->compare("login", $this->login, true);
		$criteria->compare("password", $this->password, true);
		$criteria->compare("name", $this->name, true);
		$criteria->compare("email", $this->email, true);
		$criteria->compare("active", $this->active);

		return new CActiveDataProvider($this, array(
			"criteria"=>$criteria,
		));
	}

	public function afterFind()
	{
		parent::afterFind();

		$this->fio = $this->name;
	}

	public function getRoleNames(){
		$out = array();

		foreach ($this->roles as $i => $role) {
			$out[$role->role->id] = $role->role->name;
		}

		return $out;
	}

	public function beforeDelete() {
		parent::beforeDelete();

		foreach ($this->roles as $key => $role) {
			$role->delete();
		}

		return true;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
