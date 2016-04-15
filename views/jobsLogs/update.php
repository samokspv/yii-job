<?php
/* @var $this JobsLogsController */
/* @var $model JobsLogs */

$this->breadcrumbs = array(
    'Jobs Logs' => array('index'),
    $model->id => array('view','id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'List JobsLogs', 'url' => array('index')),
    array('label' => 'View JobsLogs', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Manage JobsLogs', 'url' => array('admin')),
);
?>

<h1>Update JobsLogs <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>
