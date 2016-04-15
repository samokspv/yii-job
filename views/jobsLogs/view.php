<?php
/* @var $this JobsLogsController */
/* @var $model JobsLogs */

$this->breadcrumbs = array(
    'Jobs Logs' => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => 'List JobsLogs', 'url' => array('index')),
    array('label' => 'Update JobsLogs', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete JobsLogs', 'url' => '#', 'linkOptions' => array('submit' => array('delete','id' => $model->id),'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage JobsLogs', 'url' => array('admin')),
);
?>

<h1>View JobsLogs #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'job_id',
        'finish_message',
        'create_time',
    ),
)); ?>
