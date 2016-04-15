CREATE TABLE `job` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `job_class` varchar(64) NOT NULL,
  `job_data` text,
  `job_comment` text,
  `crontab` varchar(128) DEFAULT NULL,
  `planned_time` datetime DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `finish_time` datetime DEFAULT NULL,
  `job_status_id` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `job_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `finish_message` text,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE job ADD KEY job_class(`job_class`);
ALTER TABLE job ADD KEY job_data(job_data(255));
ALTER TABLE job ADD KEY planned_time(`planned_time`);