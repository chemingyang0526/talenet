<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
* @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
* @ORM\Table(name="users")
*/
class User implements UserInterface, \Serializable
{
	/**
	  @ORM\Column(type="integer")
	  @ORM\Id
	  @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id;
	
	/**
	  @ORM\Column(type="string", length=100)
	*/
	private $name;
	/**
	  @ORM\Column(type="string", length=100)
	*/
	private $email;
	/**
	  @ORM\Column(type="boolean")
	*/
	private $isactive;
	/**
	  @ORM\Column(type="string", length=256)
	*/
	private $password;

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
     *
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
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isactive
     *
     * @param boolean $isactive
     *
     * @return User
     */
    public function setIsactive($isactive)
    {
        $this->isactive = $isactive;

        return $this;
    }

    /**
     * Get isactive
     *
     * @return boolean
     */
    public function getIsactive()
    {
        return $this->isactive;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles() {
        return array('ROLE_USER');
    }

    public function getSalt() {
        return null;
    }

    public function getUsername() {
        return $this->name;
    }

    public function eraseCredentials() {
    
    }

    public function serialize() {
        return serialize(array($this->id, $this->name, $this->email, $this->password, $this->isactive));
    }

    public function unserialize($serialized) {
        list($this->id, $this->name, $this->email, $this->password, $this->isactive) = unserialize($serialized);
    }
}
