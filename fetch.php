<?php namespace OpenFuego;

if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50300) {
	die(__NAMESPACE__ . ' requires PHP 5.3.0 or higher.');
}

if (php_sapi_name() != 'cli') {
	die('This script must be invoked from the command line.');
}

if (in_array('-v', $argv)) {
	define(__NAMESPACE__ . '\VERBOSE', TRUE);
}

else {
	define(__NAMESPACE__ . '\VERBOSE', FALSE);
}

require_once(__DIR__ . '/init.php');

if (!function_exists('pcntl_fork')) {
	$error_message = "\n"
		. 'To start OpenFuego, run these commands:'
		. "\n\n"
		. "\tnohup " . \PHP_BINDIR . '/php ' . BASE_DIR . '/openfuego-collect.php > /dev/null 2> /dev/null & echo $!'
		. "\n"
		. "\tnohup " . \PHP_BINDIR . '/php ' . BASE_DIR . '/openfuego-consume.php > /dev/null 2> /dev/null & echo $!'
		. "\n\n";

	die($error_message);
}

pcntl_signal(SIGHUP, SIG_IGN);

$pids = array();

$pids[0] = pcntl_fork();

if (!$pids[0]) {	
	include_once(__DIR__ . '/collect.php');
}

$pids[1] = pcntl_fork();

if (!$pids[1]) {

	function sig_handler($signo) {
		switch ($signo) {
			case SIGTERM:
				// handle shutdown tasks
				global $_should_stop;
				$_should_stop = TRUE;
				break;
			case SIGINT:
				// handle ^C
				global $_should_stop;
				$_should_stop = TRUE;
				break;
			default:
				// handle all other signals
				global $_should_stop;
				$_should_stop = TRUE;
				break;
		}
	}

	pcntl_signal(SIGTERM, '\OpenFuego\sig_handler');
	pcntl_signal(SIGINT, '\OpenFuego\sig_handler');

	include_once(__DIR__ . '/consume.php');
}

echo __NAMESPACE__ . ' collector running as PID ' . $pids[0] . "\n";
echo __NAMESPACE__ . ' consumer running as PID ' . $pids[1] . "\n";

@file_put_contents(TMP_DIR . '/OpenFuego-collect.pid', $pids[0]);
@file_put_contents(TMP_DIR . '/OpenFuego-consume.pid', $pids[1]);

exit;
?>