<?php
/* @var $this JobsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Jobs',
);

$this->menu = array(
    array('label' => 'Manage Jobs', 'url' => array('admin')),
);
?>

<h1>Jobs</h1>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
)); ?>
