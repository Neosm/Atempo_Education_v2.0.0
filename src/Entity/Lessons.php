<?php

namespace App\Entity;

use App\Repository\LessonsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonsRepository::class)]
class Lessons
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $thumnail = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 10)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'lessons')]
    private ?Programs $program = null;

    #[ORM\ManyToOne(inversedBy: 'lessons')]
    private ?Schools $school = null;

    #[ORM\ManyToMany(targetEntity: Users::class, inversedBy: 'lessons')]
    private Collection $users;

    #[ORM\OneToMany(targetEntity: LessonsAudios::class, mappedBy: 'lesson')]
    private Collection $lessonsAudios;

    #[ORM\OneToMany(targetEntity: LessonsPdf::class, mappedBy: 'lesson')]
    private Collection $lessonsPdfs;

    #[ORM\OneToMany(targetEntity: LessonsVideos::class, mappedBy: 'lesson')]
    private Collection $lessonsVideos;

    #[ORM\ManyToMany(targetEntity: Evaluations::class, mappedBy: 'lesson')]
    private Collection $evaluations;

    #[ORM\ManyToMany(targetEntity: Courses::class, mappedBy: 'lessons')]
    private Collection $courses;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->lessonsAudios = new ArrayCollection();
        $this->lessonsPdfs = new ArrayCollection();
        $this->lessonsVideos = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->courses = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
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

    public function getThumnail(): ?string
    {
        return $this->thumnail;
    }

    public function setThumnail(string $thumnail): static
    {
        $this->thumnail = $thumnail;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getProgram(): ?Programs
    {
        return $this->program;
    }

    public function setProgram(?Programs $program): static
    {
        $this->program = $program;

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
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(Users $user): static
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, LessonsAudios>
     */
    public function getLessonsAudios(): Collection
    {
        return $this->lessonsAudios;
    }

    public function addLessonsAudio(LessonsAudios $lessonsAudio): static
    {
        if (!$this->lessonsAudios->contains($lessonsAudio)) {
            $this->lessonsAudios->add($lessonsAudio);
            $lessonsAudio->setLesson($this);
        }

        return $this;
    }

    public function removeLessonsAudio(LessonsAudios $lessonsAudio): static
    {
        if ($this->lessonsAudios->removeElement($lessonsAudio)) {
            // set the owning side to null (unless already changed)
            if ($lessonsAudio->getLesson() === $this) {
                $lessonsAudio->setLesson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LessonsPdf>
     */
    public function getLessonsPdfs(): Collection
    {
        return $this->lessonsPdfs;
    }

    public function addLessonsPdf(LessonsPdf $lessonsPdf): static
    {
        if (!$this->lessonsPdfs->contains($lessonsPdf)) {
            $this->lessonsPdfs->add($lessonsPdf);
            $lessonsPdf->setLesson($this);
        }

        return $this;
    }

    public function removeLessonsPdf(LessonsPdf $lessonsPdf): static
    {
        if ($this->lessonsPdfs->removeElement($lessonsPdf)) {
            // set the owning side to null (unless already changed)
            if ($lessonsPdf->getLesson() === $this) {
                $lessonsPdf->setLesson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LessonsVideos>
     */
    public function getLessonsVideos(): Collection
    {
        return $this->lessonsVideos;
    }

    public function addLessonsVideo(LessonsVideos $lessonsVideo): static
    {
        if (!$this->lessonsVideos->contains($lessonsVideo)) {
            $this->lessonsVideos->add($lessonsVideo);
            $lessonsVideo->setLesson($this);
        }

        return $this;
    }

    public function removeLessonsVideo(LessonsVideos $lessonsVideo): static
    {
        if ($this->lessonsVideos->removeElement($lessonsVideo)) {
            // set the owning side to null (unless already changed)
            if ($lessonsVideo->getLesson() === $this) {
                $lessonsVideo->setLesson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evaluations>
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluations $evaluation): static
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations->add($evaluation);
            $evaluation->addLesson($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluations $evaluation): static
    {
        if ($this->evaluations->removeElement($evaluation)) {
            $evaluation->removeLesson($this);
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
            $course->addLesson($this);
        }

        return $this;
    }

    public function removeCourse(Courses $course): static
    {
        if ($this->courses->removeElement($course)) {
            $course->removeLesson($this);
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
