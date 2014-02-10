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

class Papertrail_Log_Writer extends Log_Writer 
{
	
	private $url;
	private $name;
	private $port;
	private $levels;
	
	/**
	 * Numeric log level to string lookup table.
	 * @var array
	 */
	protected $_environments = array(
		Kohana::PRODUCTION	=> 'PRODUCTION',
		Kohana::STAGING   	=> 'STAGING',
		Kohana::TESTING    	=> 'TESTING',
		Kohana::DEVELOPMENT => 'DEVELOPMENT'
	);	
	
	/**
	 * Constructor
	 * @param string $levels
	 * @param string $name
	 * @param number $port
	 * @param string $url
	 * @throws Exception
	 */
	public function __construct($levels = null, $name = null, $port = 0, $url = null)
	{
		// Log levels
		if (isset($levels) AND is_array($levels)) $this->levels = $levels;
		else $this->levels = Kohana::$config->load('papertrail.levels');
    	// Name
		if (isset($name) AND trim($name) != '') $this->name = $name;
		else $this->name = Kohana::$config->load('papertrail.name');
		// Port
    	if (isset($port) AND $port > 0) $this->port = $port;
    	else $this->port = (int)Kohana::$config->load('papertrail.port');
    	// Papertrail URL
    	if (isset($url) AND trim($url) != '') $this->url = $url;
    	else $this->url = Kohana::$config->load('papertrail.url');
    	// Throw some exceptions
    	if ($this->port == 0 OR ! isset($this->port))
    	{
	    	throw new Exception("Papertrail port cannot be 0 or NULL");
    	}
    }

	/**
     * Write an array of messages.
     *
     *     $writer->write($messages);
     *
     * @param   array  messages
     * @return  void
     */
    public function write(array $messages) 
    {
    	if (isset($messages) AND count($messages) > 0)
    	{
    		// Component
    		$component = 'web';
    		if (empty($_SERVER['SERVER_ADDR'])) $component = 'cli';
    		// Name
    		$program = $this->name;
    		if (Kohana::$environment != Kohana::PRODUCTION)
    		{
    			$program .= '_'.$this->_environments[Kohana::$environment];
    		}
    		foreach ($messages as $message)
    		{
    			if (in_array($message['level'], $this->levels))
    			{
    				$this->send_remote_syslog(
    					$this->_log_levels[$message['level']].': '.$message['body'], 
    					$component, 
    					$program
    				);
    			}
    		}
    	}
    }
    
    /**
     * Send to remote syslog
     * 
     * https://gist.github.com/troy/2220679
     * 
     * @param unknown $message
     * @param string $component
     * @param string $program
     */
    private function send_remote_syslog($message, $component = "web", $program = "program") 
    {
    	$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    	foreach (explode("\n", $message) as $line) 
    	{
    		$syslog_message = "<22>".date('M d H:i:s ').$program.' '.$component . ': '.$line;
    		socket_sendto($sock, $syslog_message, strlen($syslog_message), 0, $this->url, $this->port);
    	}
    	socket_close($sock);
    }

} // END class Papertrail_Log_Writer
