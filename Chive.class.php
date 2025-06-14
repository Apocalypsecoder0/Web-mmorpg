
<?php
// Base::Chive.class.php
class Chive
{
	// General Info
	/**
	 * Name of the class
	 *
	 * @var String $name
	 */
	var	$name			= null;
	
	/**
	 * Database table prefix
	 *
	 * @var String $db_prefix
	 */
	var	$db_prefix		= null;

	/**
	 * Database server location
	 *
	 * @var String $db_server
	 */
	var		$db_server		= null;

	/**
	 * Database name
	 *
	 * @var String $db_name
	 */
	var		$db_name		= null;

	/**
	 * Database username
	 *
	 * @var String $db_username
	 */
	var		$db_username	= null;

	/**
	 * Database password
	 *
	 * @var String $db_password
	 */
	var		$db_password	= null;

	/**
	 * Database port
	 *
	 * @var String $db_port
	 */
	var		$db_port		= null;

	/**
	 * PDO connection link for database connections
	 *
	 * @var PDO $db_link
	 */
	var 	$db_link		= null;
	var 	$queryCount		= 0;
	/**
	 * Constructor for Chive
	 * @param String $name Name of the class
	 *
	 */
	function Chive($name = "")
	{
		global $conf;
		$this->name			= $name;
		$this->db_server	= $conf['db_server'];
		$this->db_name		= $conf['db_name'];
		$this->db_username	= $conf['db_username'];
		$this->db_password	= $conf['db_password'];
		$this->db_port		= $conf['db_port'];
		$this->db_prefix	= $conf['db_prefix'];
		
		Debug::printMsg(__CLASS__, __FUNCTION__, "Class created with <b>\$name</b> ".$this->name);
	}
	
	/**
	 * Creates a PDO connection link to $db_link
	 *
	 */
	function connectToDB()
	{
		Debug::printMsg(__CLASS__, __FUNCTION__, "Connecting to DB...");
		try {
			$dsn = "pgsql:host={$this->db_server};port={$this->db_port};dbname={$this->db_name}";
			$this->db_link = new PDO($dsn, $this->db_username, $this->db_password, [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
			]);
			Debug::printMsg(__CLASS__, __FUNCTION__, "Connected to database and selected ".$this->db_name);
		} catch (Exception $e) {
			Debug::printMsg(__CLASS__, __FUNCTION__, "Couldn't connect to DB ".$e->getMessage());
		}
	}
	
	/**
	 * Checks if a connection to the database server has been made
	 *
	 * @return bool
	 */
	function connected()
	{
		if($this->db_link)
		{
			return true;
		}
		return false;
	}
	
	/**
	 * Cleans $string for a PDO statement
	 *
	 * @param String $string
	 * @return String
	 */
	function clean_sql($string, $quotes = 1)
	{
		if(!$this->connected()) $this->connectToDB();
		
		// Stripslashes
		if (get_magic_quotes_gpc())
		{
			$string = stripslashes($string);
		}
		// Quote if not integer - PDO handles this automatically with prepared statements
		if (!is_numeric($string) && $quotes)
		{
			$string = $this->db_link->quote($string);
		}
		return $string;
	}
	
	/**
	 * Queries Database.
	 * Returns PDOStatement or false depending on success
	 *
	 * @param String $query
	 * @return PDOStatement|bool
	 */
	function query($query)
	{
		if(!$this->connected()) $this->connectToDB();
		
		try {
			$r = $this->db_link->query($query);
			Debug::printMsg(__CLASS__, __FUNCTION__, "Query successful: ".$query."\r\n");
			$this->queryCount++;
			return $r;
		} catch (Exception $e) {
			Debug::printMsg(__CLASS__, __FUNCTION__, "Query unsuccessful - <b>ERROR:</b> ".$e->getMessage()." FROM QUERY - \"".$query."\"\n");
			$this->queryCount++;
			return false;
		}
	}
}

class page_gen {
        //
        // PRIVATE CLASS VARIABLES
        //
        var $_start_time;
        var $_stop_time;
        var $_gen_time;
        
        //
        // USER DEFINED VARIABLES
        //
        var $round_to;
        
        //
        // CLASS CONSTRUCTOR
        //
        function page_gen() {
            if (!isset($this->round_to)) {
                $this->round_to = 4;
            }
        }
        
        //
        // FIGURE OUT THE TIME AT THE BEGINNING OF THE PAGE
        //
        function start() {
            $microstart = explode(' ',microtime());
            $this->_start_time = $microstart[0] + $microstart[1];
        }
        
        //
        // FIGURE OUT THE TIME AT THE END OF THE PAGE
        //
        function stop() {
            $microstop = explode(' ',microtime());
            $this->_stop_time = $microstop[0] + $microstop[1];
        }
        
        //
        // CALCULATE THE DIFFERENCE BETWEEN THE BEGINNNG AND THE END AND RETURN THE VALUE
        //
        function gen() {
            $this->_gen_time = round($this->_stop_time - $this->_start_time,$this->round_to);
            return $this->_gen_time; 
        }
    } 
?>
