<?php
/* @var $this JobsLogsController */
/* @var $model JobsLogs */

$this->breadcrumbs = array(
    'Jobs Logs' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List JobsLogs', 'url' => array('index')),
    array('label' => 'Manage JobsLogs', 'url' => array('admin')),
);
?>

<h1>Create JobsLogs</h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>
