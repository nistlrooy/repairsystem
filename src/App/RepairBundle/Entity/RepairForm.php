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
     * @var integer
     *
     * @ORM\Column(name="condition_id", type="integer")
     */
    private $conditionId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update_time", type="datetime")
     */
    private $lastUpdateTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="last_update_user", type="integer")
     */
    private $lastUpdateUser;



    /**
     * @var integer
     *
     * @ORM\Column(name="repair_task_id", type="integer")
     */
    private $repairTaskId;



    /**
     * @var integer
     *
     * @ORM\Column(name="repair_form_group_id", type="integer")
     */
    private $repairFormGroupId;


    /**
     * @Assert\Type(type="RepairBundle\Entity\FaultInfo")
     * @Assert\Valid()
     */
    protected $faultInfo;

    //嵌入FaultInfo

    public function getFaultInfo()
    {
        return $this->faultInfo;
    }

    public function setFaultInfo(FaultInfo $faultInfo = null)
    {
        $this->faultInfo = $faultInfo;
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
     * Set conditionId
     *
     * @param integer $conditionId
     * @return RepairForm
     */
    public function setConditionId($conditionId)
    {
        $this->conditionId = $conditionId;

        return $this;
    }

    /**
     * Get conditionId
     *
     * @return integer 
     */
    public function getConditionId()
    {
        return $this->conditionId;
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
     * Set lastUpdateUser
     *
     * @param integer $lastUpdateUser
     * @return RepairForm
     */
    public function setLastUpdateUser($lastUpdateUser)
    {
        $this->lastUpdateUser = $lastUpdateUser;

        return $this;
    }

    /**
     * Get lastUpdateUser
     *
     * @return integer 
     */
    public function getLastUpdateUser()
    {
        return $this->lastUpdateUser;
    }

    /**
     * Set repairTaskId
     *
     * @param integer $repairTaskId
     * @return RepairForm
     */
    public function setRepairTaskId($repairTaskId)
    {
        $this->repairTaskId = $repairTaskId;

        return $this;
    }

    /**
     * Get repairTaskId
     *
     * @return integer 
     */
    public function getRepairTaskId()
    {
        return $this->repairTaskId;
    }

    /**
     * Set repairFormGroupId
     *
     * @param integer $repairFormGroupId
     * @return RepairForm
     */
    public function setRepairFormGroupId($repairFormGroupId)
    {
        $this->repairFormGroupId = $repairFormGroupId;

        return $this;
    }

    /**
     * Get repairFormGroupId
     *
     * @return integer 
     */
    public function getRepairFormGroupId()
    {
        return $this->repairFormGroupId;
    }
}
