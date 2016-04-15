<?php
/* @var $this JobsController */
/* @var $model Jobs */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'jobs-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'job_class'); ?>
		<?php echo $form->textField($model, 'job_class', array('size' => 60, 'maxlength' => 64)); ?>
		<?php echo $form->error($model, 'job_class'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'job_data'); ?>
		<?php echo $form->textArea($model, 'job_data', array('rows' => 6, 'cols' => 50)); ?>
		<?php echo $form->error($model, 'job_data'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'crontab'); ?>
		<?php echo $form->textField($model, 'crontab', array('size' => 60, 'maxlength' => 128)); ?>
		<?php echo $form->error($model, 'crontab'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'planned_time'); ?>
		<?php echo $form->textField($model, 'planned_time'); ?>
		<?php echo $form->error($model, 'planned_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'start_time'); ?>
		<?php echo $form->textField($model, 'start_time'); ?>
		<?php echo $form->error($model, 'start_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'finish_time'); ?>
		<?php echo $form->textField($model, 'finish_time'); ?>
		<?php echo $form->error($model, 'finish_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'job_status_id'); ?>
		<?php echo $form->textField($model, 'job_status_id'); ?>
		<?php echo $form->error($model, 'job_status_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'create_time'); ?>
		<?php echo $form->textField($model, 'create_time'); ?>
		<?php echo $form->error($model, 'create_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'update_time'); ?>
		<?php echo $form->textField($model, 'update_time'); ?>
		<?php echo $form->error($model, 'update_time'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
