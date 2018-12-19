<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=191, unique=true)
   */
  private $username;

  /**
   * @ORM\Column(type="string", length=191, unique=true)
   */
  private $password;

  /**
   * @ORM\Column(type="string", length=191)
   */
  private $email;

  /**
   * @ORM\Column(type="array")
   */
  private $roles = [];

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getUsername(): ?string
  {
    return $this->username;
  }

  public function setUsername(string $username): self
  {
    $this->username = $username;

    return $this;
  }

  public function getPassword(): ?string
  {
    return $this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

  public function getRoles(): array
  {
    $roles = $this->roles;

//    if (!in_array('ROLE_USER', $roles)) {
//      $roles[] = 'ROLE_USER';
//    }
    return $roles;
  }

  public function setRoles(array $roles)
  {
    $this->roles = $roles;
  }

  public function resetRoles()
  {
    $this->roles = [];
  }

  public function getSalt()
  {
  }

  public function eraseCredentials()
  {
  }

  public function serialize()
  {
    return serialize([
      $this->id,
      $this->username,
      $this->email,
      $this->password
    ]);
  }

  public function unserialize($string)
  {
    list (
      $this->id,
      $this->username,
      $this->email,
      $this->password
      ) = unserialize($string, ['allowed_classes' => false]);
  }
}
