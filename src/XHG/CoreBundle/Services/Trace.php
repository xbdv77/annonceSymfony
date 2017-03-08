<?php

namespace XHG\CoreBundle\Services;

/**
 * Description of Trace
 *
 * @author xhg
 */
class Trace
{

    /**
     * nom du fichier de log
     */
    private $_logName = "trace.log";

    /**
     * @var Zend_Log
     */
    private $_log = null;

    /**
     * record time
     * @var array 
     */
    private $_flagRecord = array();

    /**
     * 
     * @var string
     */
    private $_buffer;

    /**
     * renvoi C:\temp sous windows
     * renvoi /tmp sinon
     * @return string temp directory
     */
    private function getTmpDir()
    {
        if (preg_match('/win/i', PHP_OS)) {
            return "C:" . DIRECTORY_SEPARATOR . "temp";
        }
        return "/tmp";
    }

    /**
     * fabrique au besoin et renvoi un objet Zend_Log
     * @return Zend_Log
     */
    private function getLog()
    {
        if (self::$_log === NULL) {
            self::$_log = new Zend_Log();
            $writer = new Zend_Log_Writer_Stream(
                    self::getTmpDir() . DIRECTORY_SEPARATOR . self::$_logName
            );
            self::$_log->addWriter($writer);
        }
        return self::$_log;
    }

    /**
     * memorisation d'une string pour affichage ulterieur
     * @param string $str 
     */
    public static function appendBuffer($str)
    {
        self::$_buffer .= $str;
    }

    /**
     * log msg in a file
     * @param string $msg
     */
    public static function log($msg)
    {
        $log = self::getLog();
        //$log->info($msg . " : " . xdebug_time_index());
        $log->info($msg);
    }

    /**
     * calculate time spend beetween 2 identicals flags
     * 
     * @param string $flag
     * @param boolean $purgeBuffer
     */
    public static function flag($flag, $purgeBuffer = true)
    {
        if (!isset(self::$_flagRecord[$flag])) {
            self::$_flagRecord[$flag] = microtime(true);
        } else {
            $log = self::getLog();
            $now = microtime(true);
            $timeSpendInFlag = $now - self::$_flagRecord[$flag];
            self::$_flagRecord[$flag] = $now;
            $msg = self::$_buffer . "\n" . "time spend between last <$flag> flag : " . $timeSpendInFlag . " at " . $now;
            $log->info($msg);

            if ($purgeBuffer) {
                self::$_buffer = '';
            }
        }
    }

    /**
     * var_dump the param into log file
     * @param mixed $foo
     */
    public static function dump($foo, $deep = NULL)
    {
        if (!is_null($deep)) {
            ini_set('xdebug.var_display_max_depth', $deep);
        }
        ob_start();
        var_dump($foo);
        self::log(ob_get_clean());
    }

    /**
     * var_dump de la pile d'appel de la fonction
     * @throws Opitml_Exception_Trace
     */
    public static function dumpTrace()
    {
        try {
            throw new Opitml_Exception_Trace ();
        } catch (Opitml_Exception_Trace $ex) {
            self::dump($ex->getTraceAsString());
        }
    }

}
