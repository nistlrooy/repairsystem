<?php

namespace App\RepairBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FaultInfo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\RepairBundle\Entity\FaultInfoRepository")
 */
class FaultInfo
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
     *
     * @ORM\ManyToOne(targetEntity="FaultType",inversedBy="faultInfo")
     * @ORM\JoinColumn(name="fault_type_id", referencedColumnName="id")
     */
    private $faultType;

    /**
     * @ORM\ManyToOne(targetEntity="App\UserBundle\Entity\Group",inversedBy="faultInfo")
     * @ORM\JoinColumn(name="location_id",referencedColumnName="id")
     *
     */
    private $group;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     * @Assert\Length(max=255)
     * @ORM\Column(name="reporter_description", type="text")
     */
    private $reporterDescription;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     * @Assert\Length(max=100)
     * @ORM\Column(name="fault_title", type="text")
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     * @Assert\Length(max=255)
     * @ORM\Column(name="worker_description", type="text")
     */
    private $workerDescription;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     * @Assert\Length(max=255)
     * @ORM\Column(name="maintenance_schedule", type="text")
     */
    private $maintenanceSchedule;

    /**
     * @ORM\ManyToOne(targetEntity="FaultPriority",inversedBy="faultInfo")
     * @ORM\JoinColumn(name="fault_priority_id", referencedColumnName="id")
     *
     *
     */
    private $faultPriority;



    /**
     * @ORM\ManyToOne(targetEntity="FaultOrder",inversedBy="faultInfo")
     * @ORM\JoinColumn(name="fault_order_id", referencedColumnName="id")
     */
    private $faultOrder;




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
     * Set reporterDescription
     *
     * @param string $reporterDescription
     * @return FaultInfo
     */
    public function setReporterDescription($reporterDescription)
    {
        $this->reporterDescription = $reporterDescription;

        return $this;
    }

    /**
     * Get reporterDescription
     *
     * @return string 
     */
    public function getReporterDescription()
    {
        return $this->reporterDescription;
    }

    /**
     * Set workerDescription
     *
     * @param string $workerDescription
     * @return FaultInfo
     */
    public function setWorkerDescription($workerDescription)
    {
        $this->workerDescription = $workerDescription;

        return $this;
    }

    /**
     * Get workerDescription
     *
     * @return string 
     */
    public function getWorkerDescription()
    {
        return $this->workerDescription;
    }

    /**
     * Set maintenanceSchedule
     *
     * @param string $maintenanceSchedule
     * @return FaultInfo
     */
    public function setMaintenanceSchedule($maintenanceSchedule)
    {
        $this->maintenanceSchedule = $maintenanceSchedule;

        return $this;
    }

    /**
     * Get maintenanceSchedule
     *
     * @return string 
     */
    public function getMaintenanceSchedule()
    {
        return $this->maintenanceSchedule;
    }


    /**
     * Set title
     *
     * @param string $title
     * @return FaultInfo
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
     * Set faultType
     *
     * @param \App\RepairBundle\Entity\FaultType $faultType
     * @return FaultInfo
     */
    public function setFaultType(\App\RepairBundle\Entity\FaultType $faultType = null)
    {
        $this->faultType = $faultType;

        return $this;
    }

    /**
     * Get faultType
     *
     * @return \App\RepairBundle\Entity\FaultType 
     */
    public function getFaultType()
    {
        return $this->faultType;
    }

    /**
     * Set group
     *
     * @param \App\UserBundle\Entity\Group $group
     * @return FaultInfo
     */
    public function setGroup(\App\UserBundle\Entity\Group $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \App\UserBundle\Entity\Group 
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set faultPriority
     *
     * @param \App\RepairBundle\Entity\FaultPriority $faultPriority
     * @return FaultInfo
     */
    public function setFaultPriority(\App\RepairBundle\Entity\FaultPriority $faultPriority = null)
    {
        $this->faultPriority = $faultPriority;

        return $this;
    }

    /**
     * Get faultPriority
     *
     * @return \App\RepairBundle\Entity\FaultPriority 
     */
    public function getFaultPriority()
    {
        return $this->faultPriority;
    }

    /**
     * Set faultOrder
     *
     * @param \App\RepairBundle\Entity\FaultOrder $faultOrder
     * @return FaultInfo
     */
    public function setFaultOrder(\App\RepairBundle\Entity\FaultOrder $faultOrder = null)
    {
        $this->faultOrder = $faultOrder;

        return $this;
    }

    /**
     * Get faultOrder
     *
     * @return \App\RepairBundle\Entity\FaultOrder 
     */
    public function getFaultOrder()
    {
        return $this->faultOrder;
    }

    public function __toString()
    {
        return $this->title;
    }

}
