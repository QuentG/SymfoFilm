<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PeopleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class People
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie", inversedBy="actors")
     */
    private $actedIn;

    public function __construct()
    {
        $this->actedIn = new ArrayCollection();
    }

	/**
	 * Execution de la fonction avant que l'entité ne soit persister et/ou update !
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 */
	public function initSlug()
	{
		// Si il n'y a pas de slug alors on le crée a partir du titre
		if(empty($this->slug)) {
			$slugify = new Slugify();
			$this->slug = $slugify->slugify($this->getFullName());
		}
	}

	/**
	 * @return string
	 */
	public function getFullName() : string
	{
		return "{$this->firstName} {$this->lastName}";
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getActedIn(): Collection
    {
        return $this->actedIn;
    }

    public function addActedIn(Movie $actedIn): self
    {
        if (!$this->actedIn->contains($actedIn)) {
            $this->actedIn[] = $actedIn;
        }

        return $this;
    }

    public function removeActedIn(Movie $actedIn): self
    {
        if ($this->actedIn->contains($actedIn)) {
            $this->actedIn->removeElement($actedIn);
        }

        return $this;
    }

    public function __toString()
	{
		return (string) $this->firstName;
	}
}
