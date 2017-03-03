<?php

namespace XHG\PlateformBundle\Antispam;

/**
 * Description of Antispam
 *
 * @author xhg
 */
class XHGAntispam
{
    private $_mailer;
    private $_locale;
    private $_minLength;
    
    public function __construct(\Swift_Mailer $mailer, $locale, $minLength)
    {
        $this->_mailer = $mailer;
        $this->_locale = $locale;
        $this->_minLength = (int) $minLength;
    }
    /**
     * Verifie si le texte est un spam ou pas
     * @param string $text
     * @return boolean
     */
    public function isSpam($text)
    {
        return strlen($text) < $this->_minLength;
    }
}
