job
=======

A simple module to have cron-like jobs in your database. In addition to yii's commands, job can be used to create on-the-fly asynchronous jobs. 
Different kinds of jobs are supported:

1. Jobs with crontab that are triggered at defined times.
2. Ad-Hoc jobs that are executed at a defined time (or as soon as possible).

To actually process the jobs you can use the JobCommand, which itself can be triggered by a sytem cron job. 
A common scenario is system a cron job that is executed once per minute to trigger JobCommand.

Database Installing
----------

Import data/mysql.sql into your database.

Yii Installing
----------

In your config add the module:

```php
'modules' => array(
		'job' => array(
		),
),
```

In your config add the JobManager component:

```php
'components'=>array(			
	'jobManager' => array(
			'class' => 'application.modules.job.components.JobManager',
			'jobs' => array(
						array(
							'class' => 'TestJob', //this is your own class that extends application.modules.job.models.Job
							'crontab' => '* 3 * * *'
						)
					)
	),
),
```

Optional: add module models import for convenience

```php
  'import'=>array(
		'application.modules.job.models.*'
	),	
```

In your console config import the JobCommand

```php
'commandMap' => array(
	'job' => 'application.modules.job.commands.JobCommand'		
),
```

Run
----------

If you want to trigger the job processing from the command line you still need a cron job that executes the JobCommand.
It should be triggers like this:

```
yiic job
```

This is the index command which will sync your jobs in the config with your database and run all jobs that are due.

Testing
----------

1. Copy file tests/TestJob.php to your project commands directory
2. Run twice from console: './yiic job' or wait one minute if you added command to cron
3. Check result(log) on UI jobs page: Job status must be 'Success'

Thanks
----------
Thanks to mtdowling for providing a nice php crontab parser: https://github.com/mtdowling/cron-expression
