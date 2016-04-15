<?php
/* @var $this JobsController */
/* @var $data Jobs */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('job_class')); ?>:</b>
	<?php echo CHtml::encode($data->job_class); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('job_data')); ?>:</b>
	<?php echo CHtml::encode($data->job_data); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('crontab')); ?>:</b>
	<?php echo CHtml::encode($data->crontab); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('planned_time')); ?>:</b>
	<?php echo CHtml::encode($data->planned_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_time')); ?>:</b>
	<?php echo CHtml::encode($data->start_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('finish_time')); ?>:</b>
	<?php echo CHtml::encode($data->finish_time); ?>
	<br />

	<?php /*
    <b><?php echo CHtml::encode($data->getAttributeLabel('job_status_id')); ?>:</b>
    <?php echo CHtml::encode($data->job_status_id); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
    <?php echo CHtml::encode($data->create_time); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
    <?php echo CHtml::encode($data->update_time); ?>
    <br />

    */ ?>

</div>
