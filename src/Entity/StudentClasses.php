<?php

namespace App\Entity;

use App\Repository\StudentClassesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentClassesRepository::class)]
class StudentClasses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'studentClasses')]
    private ?Schools $school = null;

    #[ORM\OneToMany(targetEntity: Users::class, mappedBy: 'studentClasses')]
    private Collection $students;

    #[ORM\ManyToMany(targetEntity: Courses::class, mappedBy: 'studentClasses')]
    private Collection $courses;

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->courses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    /**
     * @return Collection<int, Users>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Users $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setStudentClasses($this);
        }

        return $this;
    }

    public function removeStudent(Users $student): static
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getStudentClasses() === $this) {
                $student->setStudentClasses(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Courses>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Courses $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->addStudentClass($this);
        }

        return $this;
    }

    public function removeCourse(Courses $course): static
    {
        if ($this->courses->removeElement($course)) {
            $course->removeStudentClass($this);
        }

        return $this;
    }
}
