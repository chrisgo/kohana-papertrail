Papertrail logs via Kohana PHP (3.3x)
====

[Papertrail](http://papertrailapp.com) is a great online service that allows you to aggregate log files from all your servers and services, in order to easily keep an eye on all your systems, to grep quickly and even create alerts based on whatever shows up in the logs.

Installation
----

1. Copy the folder `kohana-papertrail` in your modules folder.
1. Create a copy of `modules/kohana-papertrail/config/papertrail.php` to `application/config/papertrail.php`
1. Edit `application/config/papertrail.php` 
   * Enter `name` to identify your application
   * Enter `port` for your account. You can find this in your papertrail account.
1. Edit your bootstrap.php file and add enable de module in your `Kohana::modules()` array:
        
        Kohana::modules(array(
            . . . . . .
            'papertrail' => MODPATH.'kohana-papertrail', // Papertrail logging service
            . . . . . .
        ));

1. Log into your Papertrail account and watch the magic.

Configuration
----

The setup above logs only ERROR and INFO level messages. To also have DEBUG, just add `Log::DEBUG` in the array above.
