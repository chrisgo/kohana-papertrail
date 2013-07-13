Papertrail logging via Kohana PHP Framework
===========================================

[Papertrail](http://papertrailapp.com) is a great online service that allows you to aggregate log files from all your servers and services, in order to easily keep an eye on all your systems, to grep quickly and even create alerts based on whatever shows up in the logs.

Installation
------------

1. Copy the folder `modules/papertrail` in your modules folder.
1. Edit `modules/papertrail/config/papertrail.php` and enter the correct port for your account. You can find this in your papertrail account.
1. Edit your bootstrap.php file and add enable de module in your `Kohana::modules()` array:
        
        Kohana::modules(array(
            . . . . . .
            'papertrail' => MODPATH.'papertrail', // Papertrail logging service
            . . . . . .
        ));

1. Define a name for this application and attach the logging module to Kohana's logging system:

        if( !defined( 'APPNAME' ) ) define('APPNAME', 'my-awesome-app');
        if( Kohana::config('papertrail.port') && class_exists( 'Papertrail_Log' ) )
            Kohana::$log->attach(new Papertrail_Log( Kohana::config('papertrail.port') ), array( Kohana::ERROR, Kohana::INFO ) );

1. Log into your Papertrail account and watch the magic.

Configuration
-------------

The setup above logs only ERROR and INFO level messages. To also have DEBUG, just add `Kohana::DEBUG` in the array above.