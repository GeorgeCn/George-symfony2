<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfileRepository")
 * @ORM\Table(name = "profile")
 */
class Profile 
{
	/**
	 * @ORM\id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $mobile_number;

    /**
     * @OneToOne(targetEntity="user",inversedBy="profile")
     * @JoinColumn(name="user_id",referencedColumnName="id")
     */
    private $user;
   

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
     * Set mobile_number
     *
     * @param integer $mobileNumber
     * @return Profile
     */
    public function setMobileNumber($mobileNumber)
    {
        $this->mobile_number = $mobileNumber;

        return $this;
    }

    /**
     * Get mobile_number
     *
     * @return integer 
     */
    public function getMobileNumber()
    {
        return $this->mobile_number;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\user $user
     * @return Profile
     */
    public function setUser(\AppBundle\Entity\user $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\user 
     */
    public function getUser()
    {
        return $this->user;
    }
}
