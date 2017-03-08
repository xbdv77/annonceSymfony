<?php

namespace XHG\PlateformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Advert
 *
 * @ORM\Table(name="advert")
 * @ORM\Entity(repositoryClass="XHG\PlateformBundle\Repository\AdvertRepository")
 * @ORM\hasLifecycleCallbacks()
 */
class Advert
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="XHG\CoreBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;
    
    /**
     * @var string
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;

    /**
     *
     * @ORM\OneToOne(targetEntity="Image")
     */
    private $image;
    
    /**
     * @ORM\ManyToMany(targetEntity="XHG\PlateformBundle\Entity\Category", cascade={"persist"})
     */
    private $categories;
    
    
    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updated_at;
    
    /**
     * @ORM\OneToMany(targetEntity="XHG\PlateformBundle\Entity\Application", mappedBy="advert")
     */
    private $applications;
    
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->categories = new ArrayCollection();
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new \DateTime());
    }
    
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Advert
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Advert
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Advert
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set author
     *
     * @param \XHG\CoreBundle\Entity\User $author
     *
     * @return Advert
     */
    public function setAuthor(\XHG\CoreBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \XHG\CoreBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set image
     *
     * @param \XHG\PlateformBundle\Entity\Image $image
     *
     * @return Advert
     */
    public function setImage(\XHG\PlateformBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \XHG\PlateformBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add category
     *
     * @param \XHG\PlateformBundle\Entity\Category $category
     *
     * @return Advert
     */
    public function addCategory(\XHG\PlateformBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \XHG\PlateformBundle\Entity\Category $category
     */
    public function removeCategory(\XHG\PlateformBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add application
     *
     * @param \XHG\PlateformBundle\Entity\Application $application
     *
     * @return Advert
     */
    public function addApplication(\XHG\PlateformBundle\Entity\Application $application)
    {
        $this->applications[] = $application;

        $application->setAdvert($this);
        return $this;
    }

    /**
     * Remove application
     *
     * @param \XHG\PlateformBundle\Entity\Application $application
     */
    public function removeApplication(\XHG\PlateformBundle\Entity\Application $application)
    {
        $this->applications->removeElement($application);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Advert
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
