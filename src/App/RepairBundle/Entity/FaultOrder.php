<?php

namespace App\RepairBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @ORM\ManyToOne(targetEntity="App\UserBundle\Entity\User",inversedBy="faultOrder")
     * @ORM\JoinColumn(name="leader_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     * @ORM\Column(name="leader_order", type="string", length=255)
     */
    private $leaderOrder;

    public function __toString()
    {
        return $this->leaderOrder;
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
     * Set user
     *
     * @param \App\UserBundle\Entity\User $user
     * @return FaultOrder
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
}
