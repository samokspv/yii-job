<?php
/* @var $this JobsLogsController */
/* @var $model JobsLogs */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model, 'id'); ?>
		<?php echo $form->textField($model, 'id', array('size' => 11, 'maxlength' => 11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'finish_message'); ?>
		<?php echo $form->textArea($model, 'finish_message', array('rows' => 6, 'cols' => 50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'create_time'); ?>
		<?php echo $form->textField($model, 'create_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'job_id'); ?>
		<?php echo $form->textField($model, 'job_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
