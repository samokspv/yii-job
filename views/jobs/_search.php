<?php
/* @var $this JobsController */
/* @var $model Jobs */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model, 'id'); ?>
		<?php echo $form->textField($model, 'id', array('size' => 10, 'maxlength' => 10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'job_class'); ?>
		<?php echo $form->textField($model, 'job_class', array('size' => 60, 'maxlength' => 64)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'job_data'); ?>
		<?php echo $form->textArea($model, 'job_data', array('rows' => 6, 'cols' => 50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'crontab'); ?>
		<?php echo $form->textField($model, 'crontab', array('size' => 60, 'maxlength' => 128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'planned_time'); ?>
		<?php echo $form->textField($model, 'planned_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'start_time'); ?>
		<?php echo $form->textField($model, 'start_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'finish_time'); ?>
		<?php echo $form->textField($model, 'finish_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'job_status_id'); ?>
		<?php echo $form->textField($model, 'job_status_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'create_time'); ?>
		<?php echo $form->textField($model, 'create_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'update_time'); ?>
		<?php echo $form->textField($model, 'update_time'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
