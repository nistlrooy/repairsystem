<?php

namespace App\RepairBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * RepairForm
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\RepairBundle\Entity\RepairFormRepository")
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
     * Constructor
     */
    public function __construct()
    {
        $this->formComment = new \Doctrine\Common\Collections\ArrayCollection();

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
}
