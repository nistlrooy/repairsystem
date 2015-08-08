<?php

namespace App\RepairBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var integer
     *
     * @ORM\Column(name="repair_form_id", type="integer")
     */
    private $repairFormId;

    /**
     * @var integer
     *
     * @ORM\Column(name="type_id", type="integer")
     */
    private $typeId;

    /**
     * @var integer
     *
     * @ORM\Column(name="location_id", type="integer")
     */
    private $locationId;

    /**
     * @var string
     *
     * @ORM\Column(name="reporter_description", type="text")
     */
    private $reporterDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="worker_description", type="text")
     */
    private $workerDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="maintenance_schedule", type="text")
     */
    private $maintenanceSchedule;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority_id", type="smallint")
     */
    private $priorityId;



    /**
     * @var integer
     *
     * @ORM\Column(name="order_id", type="integer")
     */
    private $orderId;

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
     * Set repairFormId
     *
     * @param integer $repairFormId
     * @return FaultInfo
     */
    public function setRepairFormId($repairFormId)
    {
        $this->repairFormId = $repairFormId;

        return $this;
    }

    /**
     * Get repairFormId
     *
     * @return integer 
     */
    public function getRepairFormId()
    {
        return $this->repairFormId;
    }

    /**
     * Set typeId
     *
     * @param integer $typeId
     * @return FaultInfo
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * Get typeId
     *
     * @return integer 
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set locationId
     *
     * @param integer $locationId
     * @return FaultInfo
     */
    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;

        return $this;
    }

    /**
     * Get locationId
     *
     * @return integer 
     */
    public function getLocationId()
    {
        return $this->locationId;
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
     * Set orderId
     *
     * @param integer $orderId
     * @return FaultInfo
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId
     *
     * @return integer 
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set priorityId
     *
     * @param integer $priorityId
     * @return FaultInfo
     */
    public function setPriorityId($priorityId)
    {
        $this->priorityId = $priorityId;

        return $this;
    }

    /**
     * Get priorityId
     *
     * @return integer 
     */
    public function getPriorityId()
    {
        return $this->priorityId;
    }
}
