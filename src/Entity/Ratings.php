<?php

namespace App\Entity;

use App\Repository\RatingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatingsRepository::class)]
class Ratings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    private ?Users $student = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    private ?Evaluations $evaluation = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    private ?Schools $school = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Users
    {
        return $this->student;
    }

    public function setStudent(?Users $student): static
    {
        $this->student = $student;

        return $this;
    }

    public function getEvaluation(): ?Evaluations
    {
        return $this->evaluation;
    }

    public function setEvaluation(?Evaluations $evaluation): static
    {
        $this->evaluation = $evaluation;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getSchool(): ?Schools
    {
        return $this->school;
    }

    public function setSchool(?Schools $school): static
    {
        $this->school = $school;

        return $this;
    }
}
