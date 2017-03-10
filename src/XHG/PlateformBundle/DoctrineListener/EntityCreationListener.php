<?php

namespace XHG\PlateformBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use XHG\CoreBundle\Services\Mailer;
use XHG\PlateformBundle\Entity\Application;
use XHG\PlateformBundle\Entity\Advert;

/**
 * Description of ApplicationCreationListener
 *
 * @author xhg
 */
class EntityCreationListener
{
    /**
     * @var Mailer
     */
    private $mailer;
    
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        
        if ($entity instanceof Application) {
            $this->mailer->sendNewApplicationNotification($entity);
        }
        
        if ($entity instanceof Advert) {
            $this->mailer->sendNewAdvertNotification($entity);
        }
    }
}
