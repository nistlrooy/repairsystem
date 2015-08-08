<?php

namespace App\RepairBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FaultOrder
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\RepairBundle\Entity\FaultOrderRepository")
 */
class FaultOrder
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
     * @ORM\Column(name="leader_id", type="integer")
     */
    private $leaderId;

    /**
     * @var string
     *
     * @ORM\Column(name="leader_order", type="string", length=255)
     */
    private $leaderOrder;


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
     * Set leaderOrder
     *
     * @param string $leaderOrder
     * @return FaultOrder
     */
    public function setLeaderOrder($leaderOrder)
    {
        $this->leaderOrder = $leaderOrder;

        return $this;
    }

    /**
     * Get leaderOrder
     *
     * @return string 
     */
    public function getLeaderOrder()
    {
        return $this->leaderOrder;
    }

    /**
     * Set leaderId
     *
     * @param integer $leaderId
     * @return FaultOrder
     */
    public function setLeaderId($leaderId)
    {
        $this->leaderId = $leaderId;

        return $this;
    }

    /**
     * Get leaderId
     *
     * @return integer 
     */
    public function getLeaderId()
    {
        return $this->leaderId;
    }
}
