<?php
/* @var $this JobsController */
/* @var $model Jobs */

$this->breadcrumbs = array(
    'Jobs' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List Jobs', 'url' => array('index')),
    array('label' => 'Manage Jobs', 'url' => array('admin')),
);
?>

<h1>Create Jobs</h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>
