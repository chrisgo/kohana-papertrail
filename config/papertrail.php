<?php

/**
 * Configuration file for Papertrail integration.
 */

return array (
	'port' 		=> 0,
	'name' 		=> 'KohanaApp',
	'enabled' 	=> true,
	'levels' 	=> array(Log::ERROR, Log::INFO),
	'url'  		=> 'logs.papertrailapp.com',
);