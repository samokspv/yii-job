<?php

class JobLog extends JobsLogs implements JobInterface
{
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name.
     *
     * @return JobLog the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave()
    {
        $this->finish_message = json_encode($this->finish_message);

        return parent::beforeSave();
    }

    /**
     * {@inheritdoc}
     */
    protected function afterFind()
    {
        $this->finish_message = json_decode($this->finish_message, true);
        parent::afterFind();
    }
}
