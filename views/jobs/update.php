<?php
/* @var $this JobsController */
/* @var $model Jobs */

$this->breadcrumbs = array(
    'Jobs' => array('index'),
    $model->id => array('view','id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'List Jobs', 'url' => array('index')),
    array('label' => 'View Jobs', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Manage Jobs', 'url' => array('admin')),
);
?>

<h1>Update Jobs <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>
