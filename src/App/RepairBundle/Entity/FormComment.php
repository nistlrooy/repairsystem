<?php

namespace App\RepairBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * FormComment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\RepairBundle\Entity\FormCommentRepository")
 */
class FormComment
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
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     * @Assert\Length(max=255)
     * @ORM\Column(name="comment", type="string", length=255)
     */
    private $comment;


    /**
     * @ORM\ManyToOne(targetEntity="RepairForm",inversedBy="formComment")
     * @ORM\JoinColumn(name="repair_form_id", referencedColumnName="id")
     */
    private $repairForm;




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
     * Set comment
     *
     * @param string $comment
     * @return FormComment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set repairForm
     *
     * @param \App\RepairBundle\Entity\RepairForm $repairForm
     * @return FormComment
     */
    public function setRepairForm(\App\RepairBundle\Entity\RepairForm $repairForm = null)
    {
        $this->repairForm = $repairForm;

        return $this;
    }

    /**
     * Get repairForm
     *
     * @return \App\RepairBundle\Entity\RepairForm
     */
    public function getRepairForm()
    {
        return $this->repairForm;
    }
}
