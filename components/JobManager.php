<?php

class JobManager extends CApplicationComponent
{
    /**
     * Jobs.
     *
     * @var array
     */
    public $jobs = array();

    /**
     * Clear components.
     *
     * @var array
     */
    public $clearComponents = array();

    /**
     * Execute async.
     *
     * @var boolean
     */
    public $executeAsync = true;

    /**
     * Log class name.
     *
     * @var string
     */
    protected static $logClass = "application.modules.job.components.JobManager";

    /**
     * Add job.
     *
     * @param Job $job
     *
     * @return boolean
     */
    public function addJob(Job $job, $checkEnqueuedDuplicate = true)
    {
        if ($checkEnqueuedDuplicate) {
            $duplicate = $this->findEnqueuedDuplicateJob($job);
            if ($duplicate) {
                return false;
            }
        }

        return $this->saveJob($job);
    }

    /**
     * Execute single job.
     *
     * @param Job     $job
     * @param boolean $id
     */
    public function execJob(Job $job, $id = false)
    {
        Yii::trace("Exec job directly, class = ".get_class($job));

        if (!empty($id)) {
            $job->id = $id;
        }

        $ret = $job->executeDirect();
        if ($ret != JobStatus::SUCCESS) {
            throw new Exception("Job execution was not successful: ".print_r($job->getFinishMessage(), true));
        }
    }

    /**
     * Create job from array.
     *
     * @param array $attributes
     *
     * @return Job
     */
    public function createJobFromArray($attributes)
    {
        if (!isset($attributes['class'])) {
            throw new Exception('Job needs to define a class');
        }

        $class = $attributes['class'];
        $jobData = array();
        unset($attributes['class']);

        if (isset($attributes['job_data'])) {
            $jobData = $attributes['job_data'];
            unset($attributes['job_data']);
        }

        $model = new $class();
        $model->job_class = $class;
        $model->setAttributes($attributes);
        $model->setJobData($jobData);

        return $model;
    }

    /**
     * Add crontab job from array.
     *
     * @param array $attributes
     *
     * @return Job
     */
    public function addCronTabJobFromArray($attributes)
    {
        if (!isset($attributes['class'])) {
            throw new Exception('Job needs to define a class');
        }

        if (!isset($attributes['crontab'])) {
            throw new Exception('Job needs to define a crontab value');
        }

        $model = $this->createJobFromArray($attributes);

        $models = Job::model()->findAll('t.job_class=:job_class AND t.crontab IS NOT NULL', array(':job_class' => $attributes['class']));

        foreach ($models as $compareModel) {
            if ($model->isDuplicateOf($compareModel)) {
                $model = $compareModel;
                break;
            }
        }

        if ($model->job_status_id == JobStatus::RUNNING) {
            //do not sync a job which is currently running, we do not want to break it
            return $model;
        }

        if ($attributes['crontab'] != $model->crontab) {
            Yii::trace("Setting new time");
            $model->calculateNextPlannedTime();
        }

        $this->addJob($model, true);

        return $model;
    }

    /**
     * Synchronize jobs.
     */
    public function syncJobs()
    {
        $ids = array();
        foreach ($this->jobs as $attributes) {
            if ($this->__checkLimitsProcesses($attributes)) {
                $model = $this->addCronTabJobFromArray($attributes);
                $ids[] = $model->id;
            }
        }
    }

    /**
     * Run jobs.
     */
    public function runJobs()
    {
        $tx = Yii::app()->db->beginTransaction();
        $job = null;

        try {
            $now = $this->timestampToDatabaseDate();
            $jobStatus = JobStatus::ENQUEUED;

            $sql = "SELECT * FROM job WHERE planned_time <= '{$now}' AND job_status_id = {$jobStatus}";

            $sql .= " LIMIT 1 FOR UPDATE";

            $job = Job::model()->findBySql($sql);

            if ($job) {
                $this->onBeforeExecute(new CEvent($this, array("job" => $job)));
                $job->beforeExecute();
            }

            $tx->commit();
        } catch (Exception $ex) {
            $tx->rollback();
            Yii::log("Error in finding job: ".$ex->getMessage());
        }

        if ($job) {
            if ($this->executeAsync) {
                $job->executeAsync();
            } else {
                $job->execute();
            }

            $this->onAfterExecute(new CEvent($this, array("job" => $job)));

            $this->runJobs();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onBeforeExecute($event)
    {
        $this->raiseEvent('onBeforeExecute', $event);
    }

    /**
     * {@inheritdoc}
     */
    public function onAfterExecute($event)
    {
        $this->raiseEvent('onAfterExecute', $event);
    }

    /**
     * Save job.
     *
     * @param Job $job
     *
     * @return boolean
     */
    protected function saveJob(Job $job)
    {
        if (!$job->save()) {
            Yii::log('Saving a job failed: '.print_r($job->errors, true), 'error');

            return false;
        }

        return true;
    }

    /**
     * Convert timestamp to database date.
     *
     * @param string $timestamp
     *
     * @return string
     */
    protected function timestampToDatabaseDate($timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }

        return date("Y-m-d G:i:s", $timestamp);
    }

    /**
     * Find enqueued duplicate job.
     *
     * @param Job $job
     *
     * @return Job $job
     */
    protected function findEnqueuedDuplicateJob(Job $job)
    {
        $criteria = new CDbCriteria(array(
                'condition' => 'job_class=:job_class AND job_status_id=:job_status_id',
                'params' => array(':job_class' => $job->job_class, ':job_status_id' => JobStatus::ENQUEUED),
                ));
        $jobs = Job::model()->findAll($criteria);
        if (!empty($jobs)) {
            return $jobs;
        }

        return;
    }

    /**
     * Clear components.
     */
    private function clearComponents()
    {
        foreach ($this->clearComponents as $id) {
            Yii::trace("Clear component: $id");
            Yii::app()->setComponent($id, null);
        }
    }

    /**
     * Prepare find attributes.
     *
     * @param string $class
     * @param array  $attributes
     *
     * @return array
     */
    private function prepareFindAttributes($class, $attributes)
    {
        $filterClass = $class;
        if (isset($attributes['job_class'])) {
            $filterClass = $attributes['job_class'];
        }

        if (isset($attributes['filterClass'])) {
            $filterClass = $attributes['filterClass'];
            $attributes['job_class'] = $filterClass;
            unset($attributes['filterClass']);
        }

        if (is_array($filterClass)) {
            $filterClass = $filterClass[0];
        }

        $model = $class::model();

        /* @var $filterModel Job */
        $filterModel = $filterClass::model();

        $identifierAttributes = array();
        foreach ($attributes as $name => $value) {
            if (!$model->hasAttribute($name)) {
                $identifiers = array_flip($filterModel->identifiers());
                if (isset($identifiers[$name])) {
                    unset($attributes[$name]);
                    $column = $identifiers[$name];
                    $identifierAttributes[$column] = $value;
                } else {
                    throw new Exception("Attribute {$name} not defined on ($filterClass} or mapped as identifier, defined identifiers: ".print_r($identifiers, true));
                }
            }
        }

        return array_merge($attributes, $identifierAttributes);
    }

    /**
     * Find all data.
     *
     * @param string $class
     * @param array  $attributes
     * @param string $condition
     *
     * @return object
     */
    private function findAll($class, $attributes, $condition = null)
    {
        $attributes = $this->prepareFindAttributes($class, $attributes);
        $model = $class::model();

        return $model->findAllByAttributes($attributes, $condition);
    }

    /**
     * Check limits of executed processes.
     *
     * @param array $attributes
     *
     * @return boolean
     */
    private function __checkLimitsProcesses($attributes)
    {
        if (!isset($attributes['class'])) {
            throw new Exception('Job needs to define a class');
        }

        $attributes['limit'] = !empty($attributes['limit']) ? $attributes['limit'] : false;
        exec('ps ax | grep "name="'.escapeshellarg($attributes['class']), $processes);
        $cntProcesses = count($processes) - 1;

        if ($cntProcesses > 0 &&
                (!empty($attributes['limit']) &&
                    $cntProcesses >= $attributes['limit'])
            ) {
            Yii::trace('Limit of executed processes: '.$attributes['limit']);

            return false;
        }

        return true;
    }
}
