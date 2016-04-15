<?php
/* @var $this JobsLogsController */
/* @var $model JobsLogs */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'jobs-logs-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'finish_message'); ?>
		<?php echo $form->textArea($model, 'finish_message', array('rows' => 6, 'cols' => 50)); ?>
		<?php echo $form->error($model, 'finish_message'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'create_time'); ?>
		<?php echo $form->textField($model, 'create_time'); ?>
		<?php echo $form->error($model, 'create_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'job_id'); ?>
		<?php echo $form->textField($model, 'job_id'); ?>
		<?php echo $form->error($model, 'job_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
