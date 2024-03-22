<?php

namespace App\Entity;

use App\Repository\EvaluationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvaluationsRepository::class)]
class Evaluations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'evaluations')]
    private ?Disciplines $discipline = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column(type: 'integer')]
    private $duration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $end = null;

    #[ORM\Column(type: Types::TEXT, nullable:true)]
    private ?string $comment = null;

    #[ORM\Column(type: Types::TEXT, nullable:true)]
    private ?string $objective = null;

    #[ORM\Column(length: 255)]
    private ?string $id_unique = null;

    #[ORM\ManyToMany(targetEntity: Lessons::class, inversedBy: 'evaluations')]
    private Collection $lesson;

    #[ORM\ManyToMany(targetEntity: Programs::class, inversedBy: 'evaluations')]
    private Collection $program;

    #[ORM\ManyToOne(inversedBy: 'evaluations')]
    private ?Schools $school = null;

    #[ORM\Column]
    private ?float $coefficient = null;

    #[ORM\Column]
    private ?int $max_notation = null;

    #[ORM\ManyToMany(targetEntity: Users::class, inversedBy: 'evaluations')]
    private Collection $students;

    #[ORM\ManyToOne(inversedBy: 'evaluationsteacher')]
    private ?Users $teacher = null;

    #[ORM\Column(length: 7)]
    private ?string $background_color = null;

    #[ORM\Column(length: 7)]
    private ?string $text_color = null;

    #[ORM\OneToMany(targetEntity: Absences::class, mappedBy: 'evaluation')]
    private Collection $absences;

    #[ORM\OneToMany(targetEntity: Delays::class, mappedBy: 'evaluation')]
    private Collection $delays;

    #[ORM\OneToMany(targetEntity: Ratings::class, mappedBy: 'evaluation')]
    private Collection $ratings;

    #[ORM\ManyToMany(targetEntity: StudentClasses::class, inversedBy: 'evaluations')]
    private Collection $studentclasses;

    #[ORM\ManyToOne(inversedBy: 'evaluations')]
    private ?Rooms $room = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __construct()
    {
        $this->lesson = new ArrayCollection();
        $this->program = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->absences = new ArrayCollection();
        $this->delays = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->studentclasses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): static
    {
        $this->start = $start;

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

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): static
    {
        $this->end = $end;

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

    public function getObjective(): ?string
    {
        return $this->objective;
    }

    public function setObjective(string $objective): static
    {
        $this->objective = $objective;

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

    /**
     * @return Collection<int, Lessons>
     */
    public function getLesson(): Collection
    {
        return $this->lesson;
    }

    public function addLesson(Lessons $lesson): static
    {
        if (!$this->lesson->contains($lesson)) {
            $this->lesson->add($lesson);
        }

        return $this;
    }

    public function removeLesson(Lessons $lesson): static
    {
        $this->lesson->removeElement($lesson);

        return $this;
    }

    /**
     * @return Collection<int, Programs>
     */
    public function getProgram(): Collection
    {
        return $this->program;
    }

    public function addProgram(Programs $program): static
    {
        if (!$this->program->contains($program)) {
            $this->program->add($program);
        }

        return $this;
    }

    public function removeProgram(Programs $program): static
    {
        $this->program->removeElement($program);

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

    public function getCoefficient(): ?float
    {
        return $this->coefficient;
    }

    public function setCoefficient(float $coefficient): static
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getMaxNotation(): ?int
    {
        return $this->max_notation;
    }

    public function setMaxNotation(int $max_notation): static
    {
        $this->max_notation = $max_notation;

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

    public function getTeacher(): ?Users
    {
        return $this->teacher;
    }

    public function setTeacher(?Users $teacher): static
    {
        $this->teacher = $teacher;

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
            $absence->setEvaluation($this);
        }

        return $this;
    }

    public function removeAbsence(Absences $absence): static
    {
        if ($this->absences->removeElement($absence)) {
            // set the owning side to null (unless already changed)
            if ($absence->getEvaluation() === $this) {
                $absence->setEvaluation(null);
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
            $delay->setEvaluation($this);
        }

        return $this;
    }

    public function removeDelay(Delays $delay): static
    {
        if ($this->delays->removeElement($delay)) {
            // set the owning side to null (unless already changed)
            if ($delay->getEvaluation() === $this) {
                $delay->setEvaluation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ratings>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Ratings $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setEvaluation($this);
        }

        return $this;
    }

    public function removeRating(Ratings $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getEvaluation() === $this) {
                $rating->setEvaluation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StudentClasses>
     */
    public function getStudentclasses(): Collection
    {
        return $this->studentclasses;
    }

    public function addStudentclass(StudentClasses $studentclass): static
    {
        if (!$this->studentclasses->contains($studentclass)) {
            $this->studentclasses->add($studentclass);
        }

        return $this;
    }

    public function removeStudentclass(StudentClasses $studentclass): static
    {
        $this->studentclasses->removeElement($studentclass);

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
