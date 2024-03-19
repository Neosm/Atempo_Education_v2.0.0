<?php

namespace App\Entity;

use App\Repository\DelaysRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DelaysRepository::class)]
class Delays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'delays')]
    private ?Schools $school = null;

    #[ORM\ManyToOne(inversedBy: 'delays')]
    private ?Users $student = null;

    #[ORM\ManyToOne(inversedBy: 'delays')]
    private ?Courses $course = null;

    #[ORM\ManyToOne(inversedBy: 'delays')]
    private ?Evaluations $evaluation = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $delay_time = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStudent(): ?Users
    {
        return $this->student;
    }

    public function setStudent(?Users $student): static
    {
        $this->student = $student;

        return $this;
    }

    public function getCourse(): ?Courses
    {
        return $this->course;
    }

    public function setCourse(?Courses $course): static
    {
        $this->course = $course;

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

    public function getDelayTime(): ?\DateTimeInterface
    {
        return $this->delay_time;
    }

    public function setDelayTime(\DateTimeInterface $delay_time): static
    {
        $this->delay_time = $delay_time;

        return $this;
    }
}
