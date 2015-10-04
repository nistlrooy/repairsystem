<?php

namespace App\RepairBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * RepairForm
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\RepairBundle\Entity\RepairFormRepository")
 * @ORM\HasLifecycleCallbacks
 */
class RepairForm
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
     * @ORM\ManyToOne(targetEntity="FormCondition",inversedBy="repairForm")
     * @ORM\JoinColumn(name="form_condition_id", referencedColumnName="id")
     */
    private $formCondition;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update_time", type="datetime")
     */
    private $lastUpdateTime;

    /**
     * @ORM\ManyToOne(targetEntity="App\UserBundle\Entity\User",inversedBy="repairForm")
     * @ORM\JoinColumn(name="last_update_user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\UserBundle\Entity\User",inversedBy="repairForm")
     * @ORM\JoinColumn(name="receive_user_id", referencedColumnName="id")
     */
    private $receive;

    /**
     * @ORM\ManyToOne(targetEntity="RepairTask",inversedBy="repairForm")
     * @ORM\JoinColumn(name="repair_task_id", referencedColumnName="id")
     */
    private $repairTask;

    /**
     * @ORM\OneToMany(targetEntity="FormComment",mappedBy="repairForm")
     *
     */
    private $formComment;


    /**
     * @ORM\ManyToOne(targetEntity="RepairFormGroup",inversedBy="repairForm")
     * @ORM\JoinColumn(name="repair_form_group_id", referencedColumnName="id")
     */
    private $repairFormGroup;


    /**
     * @ORM\OneToOne(targetEntity="FaultInfo",mappedBy="repairForm")
     * @ORM\JoinColumn(name="fault_info_id", referencedColumnName="id")
     * @Assert\Type(type="App\RepairBundle\Entity\FaultInfo")
     * @Assert\Valid()
     */
    protected $faultInfo;

    //嵌入FaultInfo

    /**
     * @var decimal
     * @ORM\Column(name="cost",type="decimal",precision=12,scale=2)
     *
     */
    private $cost;

    /**
     * @var integer
     *
     * @ORM\Column(name="rejectTime",type="integer")
     *
     */
    private $rejectTimes;





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
     * Set lastUpdateTime
     *
     * @param \DateTime $lastUpdateTime
     * @return RepairForm
     */
    public function setLastUpdateTime($lastUpdateTime)
    {
        $this->lastUpdateTime = $lastUpdateTime;

        return $this;
    }

    /**
     * Get lastUpdateTime
     *
     * @return \DateTime 
     */
    public function getLastUpdateTime()
    {
        return $this->lastUpdateTime;
    }

    /**
     * Set formCondition
     *
     * @param \App\RepairBundle\Entity\FormCondition $formCondition
     * @return RepairForm
     */
    public function setFormCondition(\App\RepairBundle\Entity\FormCondition $formCondition = null)
    {
        $this->formCondition = $formCondition;

        return $this;
    }

    /**
     * Get formCondition
     *
     * @return \App\RepairBundle\Entity\FormCondition 
     */
    public function getFormCondition()
    {
        return $this->formCondition;
    }

    /**
     * Set user
     *
     * @param \App\UserBundle\Entity\User $user
     * @return RepairForm
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

    /**
     * Set repairTask
     *
     * @param \App\RepairBundle\Entity\RepairTask $repairTask
     * @return RepairForm
     */
    public function setRepairTask(\App\RepairBundle\Entity\RepairTask $repairTask = null)
    {
        $this->repairTask = $repairTask;

        return $this;
    }

    /**
     * Get repairTask
     *
     * @return \App\RepairBundle\Entity\RepairTask 
     */
    public function getRepairTask()
    {
        return $this->repairTask;
    }

    /**
     * Add formComment
     *
     * @param \App\RepairBundle\Entity\FormComment $formComment
     * @return RepairForm
     */
    public function addFormComment(\App\RepairBundle\Entity\FormComment $formComment)
    {
        $this->formComment[] = $formComment;

        return $this;
    }

    /**
     * Remove formComment
     *
     * @param \App\RepairBundle\Entity\FormComment $formComment
     */
    public function removeFormComment(\App\RepairBundle\Entity\FormComment $formComment)
    {
        $this->formComment->removeElement($formComment);
    }

    /**
     * Get formComment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormComment()
    {
        return $this->formComment;
    }

    /**
     * Set repairFormGroup
     *
     * @param \App\RepairBundle\Entity\RepairFormGroup $repairFormGroup
     * @return RepairForm
     */
    public function setRepairFormGroup(\App\RepairBundle\Entity\RepairFormGroup $repairFormGroup = null)
    {
        $this->repairFormGroup = $repairFormGroup;

        return $this;
    }

    /**
     * Get repairFormGroup
     *
     * @return \App\RepairBundle\Entity\RepairFormGroup 
     */
    public function getRepairFormGroup()
    {
        return $this->repairFormGroup;
    }

    /**
     * Set faultInfo
     *
     * @param \App\RepairBundle\Entity\FaultInfo $faultInfo
     * @return RepairForm
     */
    public function setFaultInfo(\App\RepairBundle\Entity\FaultInfo $faultInfo = null)
    {
        $this->faultInfo = $faultInfo;

        return $this;
    }

    /**
     * Get faultInfo
     *
     * @return \App\RepairBundle\Entity\FaultInfo 
     */
    public function getFaultInfo()
    {
        return $this->faultInfo;
    }

    /**
     * Set receive
     *
     * @param \App\UserBundle\Entity\User $receive
     * @return RepairForm
     */
    public function setReceive(\App\UserBundle\Entity\User $receive = null)
    {
        $this->receive = $receive;

        return $this;
    }

    /**
     * Get receive
     *
     * @return \App\UserBundle\Entity\User 
     */
    public function getReceive()
    {
        return $this->receive;
    }

    /**
     * Set cost
     *
     * @param string $cost
     * @return RepairForm
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return string 
     */
    public function getCost()
    {
        return $this->cost;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formComment = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     */
    public function PrePersist()
    {
        if($this->getCost() == null)
        {
            $this->setCost(0);
        }
        if($this->getRejectTimes() == null)
        {
            $this->setRejectTimes(0);
        }
        $this->setLastUpdateTime(new \DateTime('now'));
    }

    /**
     * @ORM\PreUpdate()
     */
    public function PreUpdate()
    {
        $this->setLastUpdateTime(new \DateTime('now'));
    }


    /**
     * Set rejectTimes
     *
     * @param integer $rejectTimes
     * @return RepairForm
     */
    public function setRejectTimes($rejectTimes)
    {
        $this->rejectTimes = $rejectTimes;

        return $this;
    }

    /**
     * Get rejectTimes
     *
     * @return integer 
     */
    public function getRejectTimes()
    {
        return $this->rejectTimes;
    }
}
