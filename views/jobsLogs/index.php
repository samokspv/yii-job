<?php
/* @var $this JobsLogsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Jobs Logs',
);

$this->menu = array(
    array('label' => 'Manage JobsLogs', 'url' => array('admin')),
);
?>

<h1>Jobs Logs</h1>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
)); ?>
