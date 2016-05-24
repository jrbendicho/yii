<?php
/* @var $this Tbl_userController */
/* @var $model tbl_user */

$this->breadcrumbs=array(
	'Tbl Users'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List tbl_user', 'url'=>array('index')),
	array('label'=>'Create tbl_user', 'url'=>array('create')),
	array('label'=>'View tbl_user', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage tbl_user', 'url'=>array('admin')),
);
?>

<h1>Update tbl_user <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>