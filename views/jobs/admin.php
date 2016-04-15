<?php
/* @var $this JobsController */
/* @var $model Jobs */

$this->breadcrumbs = array(
    'Jobs' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'List Jobs', 'url' => array('index')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#jobs-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Jobs</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search', array(
    'model' => $model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'jobs-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'job_class',
        'job_data',
        'crontab',
        array(
            'name' => 'job_status_id',
            'value' => 'JobStatus::getStatusName($data["job_status_id"])',
        ),
        'create_time',
        'planned_time',
        'start_time',
        'finish_time',
        array(
            'class' => 'CButtonColumn',
            'template' => '{View}',
            'htmlOptions' => array('class' => 'bttn-clmn'),
            'buttons' => array(
                'View' => array(
                    'label' => 'log',
                    'url' => 'Yii::app()->createUrl("job/jobsLogs/admin?JobsLogs[job_id]=$data[id]")',
                    'options' => array('class' => 'act-btn'),
                ),
            ),
        ),
        array(
            'class' => 'CButtonColumn',
        ),
    ),
)); ?>
