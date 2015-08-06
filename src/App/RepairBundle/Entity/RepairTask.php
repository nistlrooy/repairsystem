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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
