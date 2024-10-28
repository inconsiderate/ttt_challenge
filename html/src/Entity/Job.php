<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(length: 1000)]
    private ?string $Description = null;

    #[ORM\ManyToMany(targetEntity: Volunteer::class, mappedBy: 'jobs')]
    private Collection $volunteers;

    public function __construct()
    {
        $this->volunteers = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getVolunteers(): array
    {
        $volunteersList = [];
        foreach($this->volunteers as $volunteer) {
            // $data = [];
            // $data['volunteer_id'] = $volunteer->getId();
            // $data['full_name'] = $volunteer->getFullName();
            // $volunteersList[] = $data;

            $volunteersList[] = $volunteer->getFullName();
        }


        return $volunteersList;
    }
}
