<?php

namespace XHG\PlateformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Advert
 *
 * @ORM\Table(name="advert")
 * @ORM\Entity(repositoryClass="XHG\PlateformBundle\Repository\AdvertRepository")
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
    
    public function __construct()
    {
        $this->date = new \DateTime();
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
}
