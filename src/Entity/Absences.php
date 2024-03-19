<?php

namespace App\Entity;

use App\Repository\AbsencesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbsencesRepository::class)]
class Absences
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'absences')]
    private ?Users $student = null;

    #[ORM\ManyToOne(inversedBy: 'absences')]
    private ?Courses $course = null;

    #[ORM\ManyToOne(inversedBy: 'absences')]
    private ?Evaluations $evaluation = null;

    #[ORM\ManyToOne(inversedBy: 'absences')]
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
