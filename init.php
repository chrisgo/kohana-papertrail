<?php defined('SYSPATH') or die('No direct script access.');

// Attach to Kohana logging
if (Kohana::$config->load('papertrail.enabled'))
{
	Kohana::$log->attach(new Papertrail_Log_Writer());
}
