<?php

namespace App\Entity;

use App\Repository\CoursesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursesRepository::class)]
class Courses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $end = null;

    #[ORM\Column(type: 'integer')]
    private $duration;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $objectives = null;

    #[ORM\Column(length: 255)]
    private ?string $id_unique = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    private ?Rooms $room = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    private ?Disciplines $discipline = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $zoom_link = null;

    #[ORM\Column]
    private ?bool $recurrence = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $recurrence_end = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $recurrence_frequency = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    private ?Schools $school = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'courses')]
    private ?self $parent = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $courses;

    #[ORM\Column(length: 7)]
    private ?string $background_color = null;

    #[ORM\Column(length: 7)]
    private ?string $text_color = null;

    #[ORM\ManyToMany(targetEntity: Programs::class, inversedBy: 'courses')]
    private Collection $programs;

    #[ORM\ManyToMany(targetEntity: Lessons::class, inversedBy: 'courses')]
    private Collection $lessons;

    #[ORM\OneToMany(targetEntity: Absences::class, mappedBy: 'course')]
    private Collection $absences;

    #[ORM\OneToMany(targetEntity: Delays::class, mappedBy: 'course')]
    private Collection $delays;

    #[ORM\ManyToOne(inversedBy: 'coursesteacher')]
    private ?Users $teacher = null;

    #[ORM\ManyToMany(targetEntity: Users::class, inversedBy: 'courses')]
    private Collection $students;

    #[ORM\ManyToMany(targetEntity: StudentClasses::class, inversedBy: 'courses')]
    private Collection $studentClasses;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->programs = new ArrayCollection();
        $this->lessons = new ArrayCollection();
        $this->absences = new ArrayCollection();
        $this->delays = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->studentClasses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): static
    {
        $this->end = $end;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

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

    public function getObjectives(): ?string
    {
        return $this->objectives;
    }

    public function setObjectives(string $objectives): static
    {
        $this->objectives = $objectives;

        return $this;
    }

    public function getIdUnique(): ?string
    {
        return $this->id_unique;
    }

    public function setIdUnique(string $id_unique): static
    {
        $this->id_unique = $id_unique;

        return $this;
    }

    public function getRoom(): ?Rooms
    {
        return $this->room;
    }

    public function setRoom(?Rooms $room): static
    {
        $this->room = $room;

        return $this;
    }

    public function getDiscipline(): ?Disciplines
    {
        return $this->discipline;
    }

    public function setDiscipline(?Disciplines $discipline): static
    {
        $this->discipline = $discipline;

        return $this;
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

    public function getZoomLink(): ?string
    {
        return $this->zoom_link;
    }

    public function setZoomLink(string $zoom_link): static
    {
        $this->zoom_link = $zoom_link;

        return $this;
    }

    public function isRecurrence(): ?bool
    {
        return $this->recurrence;
    }

    public function setRecurrence(bool $recurrence): static
    {
        $this->recurrence = $recurrence;

        return $this;
    }

    public function getRecurrenceEnd(): ?\DateTimeInterface
    {
        return $this->recurrence_end;
    }

    public function setRecurrenceEnd(?\DateTimeInterface $recurrence_end): static
    {
        $this->recurrence_end = $recurrence_end;

        return $this;
    }

    public function getRecurrenceFrequency(): ?string
    {
        return $this->recurrence_frequency;
    }

    public function setRecurrenceFrequency(?string $recurrence_frequency): static
    {
        $this->recurrence_frequency = $recurrence_frequency;

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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(self $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setParent($this);
        }

        return $this;
    }

    public function removeCourse(self $course): static
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getParent() === $this) {
                $course->setParent(null);
            }
        }

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->background_color;
    }

    public function setBackgroundColor(string $background_color): static
    {
        $this->background_color = $background_color;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->text_color;
    }

    public function setTextColor(string $text_color): static
    {
        $this->text_color = $text_color;

        return $this;
    }

    /**
     * @return Collection<int, Programs>
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Programs $program): static
    {
        if (!$this->programs->contains($program)) {
            $this->programs->add($program);
        }

        return $this;
    }

    public function removeProgram(Programs $program): static
    {
        $this->programs->removeElement($program);

        return $this;
    }

    /**
     * @return Collection<int, Lessons>
     */
    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    public function addLesson(Lessons $lesson): static
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);
        }

        return $this;
    }

    public function removeLesson(Lessons $lesson): static
    {
        $this->lessons->removeElement($lesson);

        return $this;
    }

    /**
     * @return Collection<int, Absences>
     */
    public function getAbsences(): Collection
    {
        return $this->absences;
    }

    public function addAbsence(Absences $absence): static
    {
        if (!$this->absences->contains($absence)) {
            $this->absences->add($absence);
            $absence->setCourse($this);
        }

        return $this;
    }

    public function removeAbsence(Absences $absence): static
    {
        if ($this->absences->removeElement($absence)) {
            // set the owning side to null (unless already changed)
            if ($absence->getCourse() === $this) {
                $absence->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Delays>
     */
    public function getDelays(): Collection
    {
        return $this->delays;
    }

    public function addDelay(Delays $delay): static
    {
        if (!$this->delays->contains($delay)) {
            $this->delays->add($delay);
            $delay->setCourse($this);
        }

        return $this;
    }

    public function removeDelay(Delays $delay): static
    {
        if ($this->delays->removeElement($delay)) {
            // set the owning side to null (unless already changed)
            if ($delay->getCourse() === $this) {
                $delay->setCourse(null);
            }
        }

        return $this;
    }

    public function getTeacher(): ?Users
    {
        return $this->teacher;
    }

    public function setTeacher(?Users $teacher): static
    {
        $this->teacher = $teacher;

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
        }

        return $this;
    }

    public function removeStudent(Users $student): static
    {
        $this->students->removeElement($student);

        return $this;
    }

    /**
     * @return Collection<int, StudentClasses>
     */
    public function getStudentClasses(): Collection
    {
        return $this->studentClasses;
    }

    public function addStudentClass(StudentClasses $studentClass): static
    {
        if (!$this->studentClasses->contains($studentClass)) {
            $this->studentClasses->add($studentClass);
        }

        return $this;
    }

    public function removeStudentClass(StudentClasses $studentClass): static
    {
        $this->studentClasses->removeElement($studentClass);

        return $this;
    }
}
