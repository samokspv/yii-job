<?php

class Job extends Jobs implements JobInterface
{
	/**
	 * Log class name
	 * @var string
	 */
	protected static $logClass = "application.modules.job.models.Job";

	/**
	 * Finish message
	 * @var array
	 */
	private $__finishMessage = array();

	/**
	 * Log
	 * @var array
	 */
	private $__log = array();

	/**
	 * Rollback open transaction
	 * @var boolean
	 */
	public $rollbackOpenTransaction = true;
	
	/**
	 * {@inheritdoc}
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('crontab', 'validateCrontab')
		));
	}

	/**
	 * {@inheritdoc}
	 */
    public function identifiers()
    {
        return array();
    }
	
	/**
	 * Returns job status id
	 * @return integer
	 */
	public function getJobStatusId()
	{
		return $this->job_status_id;
	}

	/**
	 * Validate crontab
	 */
	public function validateCrontab($attribute, $params)
	{
		if ($this->crontab)
		{
			try 
			{
				$cron = CronExpression::factory($this->crontab);	
			} 
			catch (InvalidArgumentException $e) 
			{
				$this->addError('crontab', 'crontab notation parsing failed');
			}
		}
	}

	/**
	 * Start now
	 */
	public function startNow()
	{
		$this->planned_time = $this->timestampToDatabaseDate(time());
	}
	
	/**
	 * Calculate next planned time
	 */
	public function calculateNextPlannedTime()
	{
		if ($this->crontab)
		{
			$cron = CronExpression::factory($this->crontab);
			$this->planned_time = $this->timestampToDatabaseDate($cron->getNextRunDate()->getTimestamp());
		}		
	}
	
	/**
     * {@inheritdoc}
     */
	public function beforeSave()
	{
    //$jobData = $this->getJobData();
    /*$identifiers = array_flip($this->identifiers());
    if($identifiers && is_array($jobData))
    {
        foreach ($jobData as $name => $value)
        {
            if (isset($identifiers[$name]))
            {
                $column = $identifiers[$name];
                $this->$column = $value;
            }
        }
    }*/

		$this->update_time = $this->timestampToDatabaseDate();
		if (!$this->create_time)
		{
			$this->create_time = $this->update_time;
		}
		//$this->job_data = json_encode($jobData);
		
		//Yii::trace("job data in before save {$this->id}: {$this->job_data}");		
		
		return parent::beforeSave();
	}
	
	/**
     * {@inheritdoc}
     */
	public function afterConstruct()
	{
		if ($this->scenario != 'search')
		{
			$this->job_class = get_class($this);
			$this->job_status_id = JobStatus::ENQUEUED;
		}
		parent::afterConstruct();
	}
	
	/**
     * {@inheritdoc}
     */
	public function beforeExecute()
	{
		$this->changeToRunningStatus();
		if (!$this->save())
			throw new Exception("Unable to save job in beforeExecute");
	}
	
	/**
     * {@inheritdoc}
     */
	public function onSuccess()
	{
		
	}
	
	/**
     * {@inheritdoc}
     */
	public function onError()
	{
	
	}
	
	/**
     * {@inheritdoc}
     */
	public function _onError()
	{
		try
		{
			$this->onError();
		}
		catch (Exception $ex)
		{
			Yii::log("Exception during job onError (ID {$this->id}): ".$ex->getMessage(), CLogger::LEVEL_ERROR);
		}
	}

	/**
	 * Execute job directly
	 * @return integer
	 */
    public function executeDirect()
    {
	    $this->changeToRunningStatus();
        $this->execute();
	    return $this->job_status_id;
    }
	
	/**
	 * Execute job asynchronous
	 * @return boolean
	 */
    public function executeAsync() {
		$command = './yiic job execJob --name=' . $this->job_class . ' --id=' . $this->id;
		shell_exec(sprintf('%s > /dev/null 2>&1 &', $command));
		return true;
    }

	/**
	 * Execute job
	 */
	public function execute()
	{
		Yii::trace("execute", self::$logClass);
		
		try
		{
			$result = $this->_execute();
			$errors = error_get_last();
			if (!empty($errors)) {
				$result = false;
				$this->__finishMessage = array(
					'job_data' => $this->job_data,
					'errors' => $errors
				);
			}

			//set default result when child execute did not set another status than RUNNING
			if ($this->job_status_id == JobStatus::RUNNING)
			{
				$this->job_status_id = $result ? JobStatus::SUCCESS : JobStatus::ERROR;
			}
			
			$this->onSuccess();
		}
		catch (Exception $e)
		{
			$this->job_status_id = JobStatus::EXCEPTION;
			$this->__finishMessage = array(
				'job_data' => $this->job_data,
				'errors' => $e->getMessage(),
				'trace' => $e->getTrace()
			);

			$this->_onError();
		}
		
		//in case the job has left a transaction, we rollback here!
		if ($this->rollbackOpenTransaction)
		{
			if ($transaction = Yii::app()->db->getCurrentTransaction())
				$transaction->rollback();
		}

		$this->afterExecute();
	}
	
	/**
     * {@inheritdoc}
     */
	public function afterExecute()
	{
		Yii::trace("after execute, jobStatus = {$this->job_status_id}", self::$logClass);

		$this->logResult();
		$this->updateJob();
	}
	
	/**
	 * Update job data
	 */
	public function updateJob() {
		$job = Job::model()->findByPk($this->id);
		$job->job_status_id = $this->job_status_id;
		$time = $this->timestampToDatabaseDate();
		$job->update_time = $time;
		$job->finish_time = $time;
		$job->save();

		// jobs callback
		if (method_exists($job, '_updateJobCallback')) {
			$this->_updateJobCallback($job);
		}
	}

	/**
	 * Returns finish message
	 * @return string
	 */
	public function getFinishMessage()
	{
		$this->__finishMessage['log'] = $this->getLog();
		return $this->__finishMessage;
	}
	
	/**
	 * Log
	 * @param string $message
	 */
	public function log($message)
	{
		$this->__log[] = $message;
	}

	/**
	 * Log
	 * @return array
	 */
	public function getLog()
	{
		return $this->__log;
	}

	/**
	 * Returns job data
	 * @return array
	 */
	public function getJobData()
	{
		return $this->job_data;
	}

	/**
	 * Set job data
	 * @param array $data
	 */
	public function setJobData($data)
	{		
		/*if (!is_array($data))
			return;
		
		foreach ($data as $attribute => $value)
		{
			$this->$attribute = $value;
		}*/
	}
	
	/**
	 * Check job is duplicate
	 * @param Job $job
	 * @return boolean
	 */
	public function isDuplicateOf($job)
	{
		return false;
	}

	/**
	 * Execute job
	 */
	protected function _execute()
	{
		throw new Exception("Illegal call to _execute. This method should have been overwritten");
	}

	/**
	 * Convert timestamp to database date
	 * @param  string $timestamp
	 * @return string
	 */
	protected function timestampToDatabaseDate($timestamp = null)
	{
		if ($timestamp === null)
		{
			$timestamp = time();
		}
		
		return date("Y-m-d G:i:s", $timestamp);
	}

	/**
     * {@inheritdoc}
     */
	protected function beforeValidate()
	{
		if ($this->planned_time === null)
		{
			$this->calculateNextPlannedTime();
			if ($this->planned_time === null)
				$this->planned_time = $this->timestampToDatabaseDate();
		}
		
		return parent::beforeValidate();
	}

	/**
     * {@inheritdoc}
     */
	protected function afterFind()
	{
		//Yii::trace("Decoding {$this->job_data}");
		//$this->setJobData(json_decode($this->job_data, true));
		parent::afterFind();
	}
	
	/**
     * {@inheritdoc}
     */
	protected function instantiate($attributes)
	{
		if ($class = $attributes['job_class'])
		{
			return new $class(null);
		}
		else
			return parent::instantiate($attributes);
	}

	/**
	 * Change job status to running
	 */
	protected function changeToRunningStatus()
	{
		$this->start_time = $this->timestampToDatabaseDate();
		$this->job_status_id = JobStatus::RUNNING;
	}

	/**
	 * Log result
	 */
	protected function logResult()
	{
		Yii::trace("log result", self::$logClass);
		$log = new JobLog();
		$log->finish_message = $this->getFinishMessage();
		$log->create_time = $this->timestampToDatabaseDate();
		$log->job_id = $this->id;

		if (!$log->save())
			return Yii::log('Saving a job log failed: ' . var_export($log->errors, true), 'error');

		if ($this->job_status_id == JobStatus::EXCEPTION)
			Yii::log("Running job {$this->job_class} resulted in an exception: " . print_r($this->extractErrorMessageFromFinishMessage($log), true) . ". Please see log ID {$log->id} for details", 'error');

		if ($this->job_status_id == JobStatus::ERROR)
			Yii::log("Running job {$this->job_class} resulted in an error: " . print_r($this->extractErrorMessageFromFinishMessage($log), true) . " Please see log ID {$log->id} for details", 'error');
	}

	/**
	 * Extract error message from finish message
	 * @param JobLog $log
	 * @return string
	 */
	private function extractErrorMessageFromFinishMessage(JobLog $log)
	{
		$finish = $log->finish_message;
		if (is_string($finish))
			$finish = json_decode($finish, true);

		if (isset($finish['errors']))
			return $finish['errors'];

		return '';
	}
}