<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BooksRepository")
 * @ORM\Table(name = "books")
 */
class Books 
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	private $id;

	/**
     * @ORM\Column(type="integer", options={"comment":"用户id"})
     */
    private $userId;

    /**
     * @ORM\Column(type="datetime", options={"comment":"创建每条记录的时间"})
     */
    private $creatTime;


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
     * Set userId
     *
     * @param integer $userId
     * @return Books
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set creatTime
     *
     * @param \DateTime $creatTime
     * @return Books
     */
    public function setCreatTime($creatTime)
    {
        $this->creatTime = $creatTime;

        return $this;
    }

    /**
     * Get creatTime
     *
     * @return \DateTime 
     */
    public function getCreatTime()
    {
        return $this->creatTime;
    }
}
