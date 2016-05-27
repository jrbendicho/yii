<?php
/**
*Auto Generated Model
*
*Program created By Sal Santoro sssantoro07@gmail.com
*
**********************************************************************************/

Class tbl_bots extends CActiveRecord
{

	public $id;
	public $name;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return 'tbl_bots';
	}

	public function primaryKey()
	{
		return 'id';
	}



	public function rules()
	{
		return array(
			array(
			'id,name,','safe'));
	}


	public function attributeLabels()
	{
		return array(
		
		'id'=>'Id',
		'name'=>'Name');
	}

}
?>