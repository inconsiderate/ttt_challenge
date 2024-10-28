<?php

namespace App\Entity;

use App\Repository\VolunteerRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: VolunteerRepository::class)]
class Volunteer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $FirstName = null;

    #[ORM\Column(length: 255)]
    private ?string $LastName = null;

    #[ORM\ManyToMany(targetEntity: Job::class, inversedBy: 'volunteers')]
    #[ORM\JoinTable(name: 'volunteers_jobs')]
    private Collection $jobs;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): static
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(string $LastName): static
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->FirstName . " " . $this->LastName;
    }
    public function getJobs(): ?array
    {
        $jobsList = [];
        foreach($this->jobs as $job) {
            $jobsList[] = $job->getName();
        }


        return $jobsList;
    }
}
