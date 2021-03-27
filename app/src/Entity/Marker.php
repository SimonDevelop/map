<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Entity\MarkerRepository")
 * @ORM\Table(name="markers")
 */
class Marker
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id_marker", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float $lat
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $lat;

    /**
     * @var float $lng
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $lng;

    /**
     * @var integer $user
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id_user", nullable=true)
     */
    private $user;

    /**
     * @var datetime $date
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->user = null;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get lat
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lat
     *
     * @param float $lat
     *
     * @return Marker
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * Get lng
     *
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set lng
     *
     * @param float $lng
     *
     * @return Marker
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
        return $this;
    }

    /**
    * Get user
    *
    * @return \Doctrine\Common\Collections\Collection
    */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param \App\Entity\User $user
     *
     * @return Marker
     */
    public function setUser(\App\Entity\User $user = null)
    {
        $this->user = $user;
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Marker
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }
}
