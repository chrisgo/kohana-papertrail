<?php defined('SYSPATH') or die('No direct script access.');

// Attach to Kohana logging
if( !defined( 'APPNAME' ) ) define('APPNAME', 'PropertyRate');
if( Kohana::$config->load('papertrail.port') && class_exists( 'Papertrail_Log' ) )
{
	Kohana::$log->attach(new Papertrail_Log(Kohana::$config->load('papertrail.port')), array(
		Log::ERROR, Log::INFO
	));
}