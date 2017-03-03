<?php

namespace XHG\PlateformBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="XHG\PlateformBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @var XHG\PlateformBundle\Entity\Advert[]
     * @ORM\OneToMany(targetEntity="XHG\PlateformBundle\Entity\Advert", mappedBy="author") 
     */
    private $adverts;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add advert
     *
     * @param \XHG\PlateformBundle\Entity\Advert $advert
     *
     * @return User
     */
    public function addAdvert(\XHG\PlateformBundle\Entity\Advert $advert)
    {
        $this->adverts[] = $advert;

        return $this;
    }

    /**
     * Remove advert
     *
     * @param \XHG\PlateformBundle\Entity\Advert $advert
     */
    public function removeAdvert(\XHG\PlateformBundle\Entity\Advert $advert)
    {
        $this->adverts->removeElement($advert);
    }

    /**
     * Get adverts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdverts()
    {
        return $this->adverts;
    }
}
