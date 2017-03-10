<?php

namespace XHG\CoreBundle\Services;

use XHG\PlateformBundle\Entity\Application;
use XHG\PlateformBundle\Entity\Advert;

/**
 * Description of Mailer
 *
 * @author xhg
 */
class Mailer
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNewAdvertNotification(Advert $advert)
    {
        $message = new \Swift_Message(
                'Création de l\'annonce',
                'L\'annonce "' . $advert->getTitle() . '" vient d\'être créée'
        );
        $message
                ->addTo($advert->getEmail())
                ->addFrom('admin@plateform.com');
        $this->mailer->send($message);
    }

    public function sendNewApplicationNotification(Application $application)
    {
        $message = new \Swift_Message(
                'Dépot de candidature',
                'Une candidature vient d\'être déposé pour l\'annonce "' . $application->getAdvert()->getTitle()
        );
        $message
                ->addTo($application->getAdvert()->getEmail())
                ->addFrom('admin@plateform.com');
        $this->mailer->send($message);
    }
}
