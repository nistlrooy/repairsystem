<?php

namespace App\RepairBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RepairTask
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\RepairBundle\Entity\RepairTaskRepository")
 */
class RepairTask
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\UserBundle\Entity\User",inversedBy="repairTask")
     * @ORM\JoinColumn(name="create_user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="create_time", type="datetime")
     *
     *
     */
    private $createTime;

    public function __construct()
    {
        $this->createTime = new \DateTime('now');
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
     * Set createTime
     *
     * @param \DateTime $createTime
     * @return RepairTask
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime
     *
     * @return \DateTime 
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Set user
     *
     * @param \App\UserBundle\Entity\User $user
     * @return RepairTask
     */
    public function setUser(\App\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \App\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /*
     * @ORM\PrePersist()
     */
    public function PrePersist()
    {
        if($this->getCreateTime() == null)
        {
            $this->setCreateTime(new \DateTime('now'));
        }
    }


}
