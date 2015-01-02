<?php

namespace sndsgd;

use \sndsgd\cli\Command;
use \sndsgd\cli\Process;
use \sndsgd\util\Path;


class Cli
{
   /**
    * Get the name of the command that was executed
    *
    * @param boolean $asCalled Set to true to return the name as it was called
    * @return string
    */
   public static function getScriptName($asCalled = false)
   {
      $name = $_SERVER['argv'][0];
      return ($asCalled === false) ? basename($name) : $name;
   }

   /**
    * Attempt to determine the path to the currently executing script
    *
    * @return string The absolute path the script that was executed
    */
   public static function getScriptPath()
   {
      if ($_SERVER['SCRIPT_FILENAME']{0} === '/') {
	 return $_SERVER['SCRIPT_FILENAME'];
      }
      else if (strpos($_SERVER['SCRIPT_NAME'], $_SERVER['PWD']) === 0) {
	 return $_SERVER['SCRIPT_NAME'];
      }
      else {
	 return Path::normalize($_SERVER['PWD'].'/'.$_SERVER['SCRIPT_NAME']);
      }
   }

   /**
    * Fork a system call
    *
    * @param string $command The command to fork
    * @param string $output A file path to redirect output to
    * @return void
    */
   public static function fork($command, $output = '/dev/null')
   {
      pclose(popen($command.' > '.escapeshellarg($output).' &', 'r'));
   }

   /**
    * Determine the user that owns the current process
    *
    * @return string the name of the current user
    */
   public static function getUser()
   {
      $cmd = Command::getPath('whoami');
      $process = new Process(escapeshellarg($cmd));
      if ($process->exec() !== 0) {

      }
      return trim($process->getStdout());
   }

   /**
    * Get the number of columns in the terminal
    *
    * @return integer
    */
   public static function getWidth()
   {
      return intval(shell_exec('tput cols'));
   }

   /**
    * Get the number of lines in the terminal
    *
    * @return integer
    */
   public static function getHeight()
   {
      return intval(shell_exec('tput lines'));
   }
}