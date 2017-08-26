<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name = "user")
 */
class User 
{
	/**
	 * @ORM\id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $email;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $password;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    protected $sex;

    /**
     * @OneToOne(targetEntity="Profile",mappedBy="user")
     */
    private $profile;

    const NAME = "George";

    private static $types = null;
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
     * Set email
     *
     * @param string $email
     * @return user
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return user
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set sex
     *
     * @param integer $sex
     * @return user
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return integer 
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set profile
     *
     * @param \AppBundle\Entity\profile $profile
     * @return user
     */
    public function setProfile(\AppBundle\Entity\profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \AppBundle\Entity\profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }

    public static function echoType($type)
    {
        if (empty(self::$types)) {
            self::$types = [
                'image' => 1,
                'text' => 2,
                'radio' => 3,
                'date' => 4,
            ];
        }
        if (isset(self::$types[$type])) {
            return self::$types[$type];
        }
        return null;
    }
}
