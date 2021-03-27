<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Entity\UserRepository")
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id_user", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $email
     *
     * @ORM\Column(type="string", unique=true, length=100)
     */
    private $email;

    /**
     * @var string $password
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string $rememberToken
     *
     * @ORM\Column(name="remember_token", type="string", nullable=true)
     */
    private $rememberToken;

    /**
     * @var string $resetToken
     *
     * @ORM\Column(name="reset_token", type="string", nullable=true)
     */
    private $resetToken;

    /**
     * @var bool $active
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var bool $admin
     *
     * @ORM\Column(name="admin", type="boolean", nullable=false)
     */
    private $admin;

    /**
     * @var string $secret
     *
     * @ORM\Column(type="string")
     */
    private $secret;

    /**
     * @var datetime $date_create
     *
     * @ORM\Column(type="datetime")
     */
    private $date_create;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rememberToken = null;
        $this->resetToken = null;
        $this->active = true;
        $this->admin = false;
        $this->date_create = new \DateTime();
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
    * Get email
    *
    * @return string
    */
    public function getEmail()
    {
        return $this->email;
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
    * Get password
    *
    * @return string
    */
    public function getPassword()
    {
        return $this->password;
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
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * Password verification
     *
     * @param string $password
     *
     * @return bool
     */
    public function passwordVerify($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * Get rememberToken
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    /**
    * Set rememberToken
    *
    * @param string $rememberToken
    *
    * @return User
    */
    public function setRememberToken($rememberToken)
    {
        $this->rememberToken = $rememberToken;
        return $this;
    }

    /**
     * Get resetToken
     *
     * @return string
     */
    public function getResetToken()
    {
        return $this->resetToken;
    }

    /**
     * Set resetToken
     *
     * @param string $resetToken
     *
     * @return User
     */
    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;
        return $this;
    }

    /**
    * Get active
    *
    * @return boolean
    */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
    * Get admin
    *
    * @return boolean
    */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set admin
     *
     * @param boolean $admin
     *
     * @return User
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
        return $this;
    }

    /**
    * Get secret
    *
    * @return string
    */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set secret
     *
     * @param string $secret
     *
     * @return User
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
    * Get date_create
    *
    * @return \DateTime
    */
    public function getDateCreate()
    {
        return $this->date_create;
    }

    /**
     * Set date_create
     *
     * @param \DateTime $date_create
     *
     * @return User
     */
    public function setDateCreate(\DateTime $date_create)
    {
        $this->date_create = $date_create;
        return $this;
    }
}
