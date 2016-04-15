<?php
class JobCommand extends CConsoleCommand
{
    /**
     * Init.
     */
    public function init()
    {
        Yii::import('job.models.*');
        Yii::import('job.vendors.crontab.*');
    }

    /**
     * Synchronize jobs.
     */
    public function actionSyncJobs()
    {
        Yii::app()->jobManager->syncJobs();
    }

    /**
     * Run jobs.
     */
    public function actionRunJobs()
    {
        Yii::app()->jobManager->runJobs();
    }

    /**
     * Run and synchronize jobs.
     */
    public function actionIndex()
    {
        Yii::app()->jobManager->runJobs();
        Yii::app()->jobManager->syncJobs();
    }

    /**
     * Execute single job.
     *
     * @param string  $name
     * @param integer $id
     */
    public function actionExecJob($name = null, $id = null)
    {
        $job = new $name();
        Yii::app()->jobManager->execJob($job, $id);
    }
}
