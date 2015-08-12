<?php

namespace App\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;


/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="App\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max="255",
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=30)
     *
     * @Assert\NotBlank(message="Please enter your phone.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max="30",
     *     minMessage="The number is too short.",
     *     maxMessage="The number is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $phone;

    /**
     * @ORM\ManyToOne(targetEntity="App\UserBundle\Entity\Group")
     * @ORM\JoinColumn(name="fos_group_id", referencedColumnName="id")
     */
    protected $group;


    public function __construct()
    {
        parent::__construct();

        //add default roles 给每个用户添加一个默认的roles
        $this->roles = array('ROLE_USER');
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
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }



    /**
     * Set group
     *
     * @param \App\UserBundle\Entity\Group $group
     * @return User
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
}
