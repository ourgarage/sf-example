<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AuthBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    const ROLE_USER = "ROLE_USER";
    const ROLE_ADMIN = "ROLE_ADMIN";
    const ROLE_ROOT = "ROLE_ROOT";

    const STATUS_ACTIVE = "active";
    const STATUS_INACTIVE = "inactive";

    const ROLES_LIST = [
        self::ROLE_USER => 'user',
        self::ROLE_ADMIN => 'admin',
        self::ROLE_ROOT => 'root',
    ];

    const STATUSES_LIST = [
        self::STATUS_ACTIVE => 'active',
        self::STATUS_INACTIVE => 'inactive',
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string")
     * @Assert\NotBlank()
     */
    private $role = self::ROLE_USER;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string")
     * @Assert\NotBlank()
     */
    private $status = self::STATUS_ACTIVE;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
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
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $nickname
     *
     * @return User
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string The user roles
     */
    public function getRoles()
    {
        if (empty($this->role)) {
            return [self::ROLE_USER];
        }

        return [$this->role];
    }

    /**
     * @return string The user roles
     */
    public function setRoles($roles)
    {
        return $this;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {}

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return json_encode([
            $this->id,
            $this->email,
            $this->nickname,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        [
            $this->id,
            $this->email,
            $this->nickname,
        ] = json_decode($serialized);
    }

    public function __toString()
    {
        return $this->getUsername();
    }
}
