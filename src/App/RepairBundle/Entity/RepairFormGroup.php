<?php

namespace App\RepairBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RepairFormGroup
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\RepairBundle\Entity\RepairFormGroupRepository")
 */
class RepairFormGroup
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
     * @ORM\Column(name="update_user_id", type="integer")
     *
     *
     */
    private $update_user_id;

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
     * Set update_user_id
     *
     * @param integer $updateUserId
     * @return RepairFormGroup
     */
    public function setUpdateUserId($updateUserId)
    {
        $this->update_user_id = $updateUserId;

        return $this;
    }

    /**
     * Get update_user_id
     *
     * @return integer 
     */
    public function getUpdateUserId()
    {
        return $this->update_user_id;
    }
}
