<?php
// Base::Debug.class.php
class Debug
{
	function printMsg($className, $function, $message)
	{
		if(DEBUG)
		{
			// Store debug messages instead of immediately outputting them
			if (!isset($GLOBALS['debug_messages'])) {
				$GLOBALS['debug_messages'] = array();
			}
			
			$debugMsg = array(
				'class' => $className,
				'function' => $function,
				'message' => $message,
				'timestamp' => date('Y-m-d H:i:s')
			);
			
			$GLOBALS['debug_messages'][] = $debugMsg;
		}
	}
	
	static function outputDebugMessages()
	{
		if (DEBUG && isset($GLOBALS['debug_messages'])) {
			echo '<div style="background: #f9f9f9; padding: 10px; margin: 10px; border: 1px solid #ddd; font-size: 12px;">';
			echo '<h4>Debug Messages:</h4>';
			foreach ($GLOBALS['debug_messages'] as $msg) {
				echo '<div><strong>' . htmlspecialchars($msg['class']) . '::' . htmlspecialchars($msg['function']) . '</strong> - ';
				echo htmlspecialchars($msg['message']) . ' <em>(' . $msg['timestamp'] . ')</em></div>';
			}
			echo '</div>';
		}
	}
}
?>