<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Log writer for Papertrailapp.com's UDP logging facility.
 *
 * @package    Papertrail
 * @category   Logging
 * @author     Lucian Daniliuc <dlucian@gmail.com>
 * @copyright  (c) 2010-2013 Certified Vision SRL
 * @license    http://opensource.org/licenses/MIT
 */

class Papertrail_Log_Writer extends Log_Writer {

    private $url = 'logs.papertrailapp.com';
    private $port = 0;

    public function __construct( $port, $url = '' ) {
        if( !empty( $url ) ) $this->url = $url;
        $this->port = (int)$port;
    } // END constructor

    /**
     * Write an array of messages.
     *
     *     $writer->write($messages);
     *
     * @param   array  messages
     * @return  void
     */
    public function write(array $messages) {
        if( $this->port ) {
            foreach( $messages as $message ) {
                $component = 'web';
                if( empty( $_SERVER['SERVER_ADDR'] ) ) $component = 'cli';
                if( !defined( 'APPNAME' ) ) $program = 'kohana_' . strtolower( Kohana::$environment );
                    else $program = constant( 'APPNAME' ) . '_' . strtolower( Kohana::$environment );
                $this->send_remote_syslog( $message['type'] . ' ' . $message['body'], $component, $program );
            }
        }
    }

    private function send_remote_syslog($message, $component = "web", $program = "next_big_thing") {
        $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        foreach(explode("\n", $message) as $line) {
            $syslog_message = "<22>" . date('M d H:i:s ') . $program . ' ' . $component . ': ' . $line;
            socket_sendto($sock, $syslog_message, strlen($syslog_message), 0, $this->url, $this->port );
        }
        socket_close($sock);
    } // END func send_remote_syslog()

} // END class Papertrail_Log
