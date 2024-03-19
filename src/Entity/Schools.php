<?php

namespace App\Entity;

use App\Repository\SchoolsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SchoolsRepository::class)]
class Schools
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $number = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $campus = null;

    #[ORM\OneToMany(targetEntity: Users::class, mappedBy: 'school')]
    private Collection $users;

    #[ORM\OneToMany(targetEntity: Programs::class, mappedBy: 'school')]
    private Collection $programs;

    #[ORM\OneToMany(targetEntity: Lessons::class, mappedBy: 'school')]
    private Collection $lessons;

    #[ORM\OneToMany(targetEntity: LessonsAudios::class, mappedBy: 'school')]
    private Collection $lessonsAudios;

    #[ORM\OneToMany(targetEntity: LessonsPdf::class, mappedBy: 'school')]
    private Collection $lessonsPdfs;

    #[ORM\OneToMany(targetEntity: LessonsVideos::class, mappedBy: 'school')]
    private Collection $lessonsVideos;

    #[ORM\OneToMany(targetEntity: Categories::class, mappedBy: 'school')]
    private Collection $categories;

    #[ORM\OneToMany(targetEntity: Posts::class, mappedBy: 'school')]
    private Collection $posts;

    #[ORM\OneToMany(targetEntity: Rooms::class, mappedBy: 'school')]
    private Collection $rooms;

    #[ORM\OneToMany(targetEntity: Disciplines::class, mappedBy: 'school')]
    private Collection $disciplines;

    #[ORM\OneToMany(targetEntity: StudentClasses::class, mappedBy: 'school')]
    private Collection $studentClasses;

    #[ORM\OneToMany(targetEntity: Courses::class, mappedBy: 'school')]
    private Collection $courses;

    #[ORM\OneToMany(targetEntity: Events::class, mappedBy: 'school')]
    private Collection $events;

    #[ORM\OneToMany(targetEntity: Evaluations::class, mappedBy: 'school')]
    private Collection $evaluations;

    #[ORM\OneToMany(targetEntity: Delays::class, mappedBy: 'school')]
    private Collection $delays;

    #[ORM\OneToMany(targetEntity: Absences::class, mappedBy: 'school')]
    private Collection $absences;

    #[ORM\OneToMany(targetEntity: Ratings::class, mappedBy: 'school')]
    private Collection $ratings;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->programs = new ArrayCollection();
        $this->lessons = new ArrayCollection();
        $this->lessonsAudios = new ArrayCollection();
        $this->lessonsPdfs = new ArrayCollection();
        $this->lessonsVideos = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->rooms = new ArrayCollection();
        $this->disciplines = new ArrayCollection();
        $this->studentClasses = new ArrayCollection();
        $this->courses = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->delays = new ArrayCollection();
        $this->absences = new ArrayCollection();
        $this->ratings = new ArrayCollection();
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

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCampus(): ?string
    {
        return $this->campus;
    }

    public function setCampus(string $campus): static
    {
        $this->campus = $campus;

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
            $user->setSchool($this);
        }

        return $this;
    }

    public function removeUser(Users $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSchool() === $this) {
                $user->setSchool(null);
            }
        }

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
            $program->setSchool($this);
        }

        return $this;
    }

    public function removeProgram(Programs $program): static
    {
        if ($this->programs->removeElement($program)) {
            // set the owning side to null (unless already changed)
            if ($program->getSchool() === $this) {
                $program->setSchool(null);
            }
        }

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
            $lesson->setSchool($this);
        }

        return $this;
    }

    public function removeLesson(Lessons $lesson): static
    {
        if ($this->lessons->removeElement($lesson)) {
            // set the owning side to null (unless already changed)
            if ($lesson->getSchool() === $this) {
                $lesson->setSchool(null);
            }
        }

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
            $lessonsAudio->setSchool($this);
        }

        return $this;
    }

    public function removeLessonsAudio(LessonsAudios $lessonsAudio): static
    {
        if ($this->lessonsAudios->removeElement($lessonsAudio)) {
            // set the owning side to null (unless already changed)
            if ($lessonsAudio->getSchool() === $this) {
                $lessonsAudio->setSchool(null);
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
            $lessonsPdf->setSchool($this);
        }

        return $this;
    }

    public function removeLessonsPdf(LessonsPdf $lessonsPdf): static
    {
        if ($this->lessonsPdfs->removeElement($lessonsPdf)) {
            // set the owning side to null (unless already changed)
            if ($lessonsPdf->getSchool() === $this) {
                $lessonsPdf->setSchool(null);
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
            $lessonsVideo->setSchool($this);
        }

        return $this;
    }

    public function removeLessonsVideo(LessonsVideos $lessonsVideo): static
    {
        if ($this->lessonsVideos->removeElement($lessonsVideo)) {
            // set the owning side to null (unless already changed)
            if ($lessonsVideo->getSchool() === $this) {
                $lessonsVideo->setSchool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Categories>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setSchool($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getSchool() === $this) {
                $category->setSchool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Posts>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Posts $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setSchool($this);
        }

        return $this;
    }

    public function removePost(Posts $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getSchool() === $this) {
                $post->setSchool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rooms>
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Rooms $room): static
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms->add($room);
            $room->setSchool($this);
        }

        return $this;
    }

    public function removeRoom(Rooms $room): static
    {
        if ($this->rooms->removeElement($room)) {
            // set the owning side to null (unless already changed)
            if ($room->getSchool() === $this) {
                $room->setSchool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Disciplines>
     */
    public function getDisciplines(): Collection
    {
        return $this->disciplines;
    }

    public function addDiscipline(Disciplines $discipline): static
    {
        if (!$this->disciplines->contains($discipline)) {
            $this->disciplines->add($discipline);
            $discipline->setSchool($this);
        }

        return $this;
    }

    public function removeDiscipline(Disciplines $discipline): static
    {
        if ($this->disciplines->removeElement($discipline)) {
            // set the owning side to null (unless already changed)
            if ($discipline->getSchool() === $this) {
                $discipline->setSchool(null);
            }
        }

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
            $studentClass->setSchool($this);
        }

        return $this;
    }

    public function removeStudentClass(StudentClasses $studentClass): static
    {
        if ($this->studentClasses->removeElement($studentClass)) {
            // set the owning side to null (unless already changed)
            if ($studentClass->getSchool() === $this) {
                $studentClass->setSchool(null);
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
            $course->setSchool($this);
        }

        return $this;
    }

    public function removeCourse(Courses $course): static
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getSchool() === $this) {
                $course->setSchool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Events>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Events $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setSchool($this);
        }

        return $this;
    }

    public function removeEvent(Events $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getSchool() === $this) {
                $event->setSchool(null);
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
            $evaluation->setSchool($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluations $evaluation): static
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getSchool() === $this) {
                $evaluation->setSchool(null);
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
            $delay->setSchool($this);
        }

        return $this;
    }

    public function removeDelay(Delays $delay): static
    {
        if ($this->delays->removeElement($delay)) {
            // set the owning side to null (unless already changed)
            if ($delay->getSchool() === $this) {
                $delay->setSchool(null);
            }
        }

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
            $absence->setSchool($this);
        }

        return $this;
    }

    public function removeAbsence(Absences $absence): static
    {
        if ($this->absences->removeElement($absence)) {
            // set the owning side to null (unless already changed)
            if ($absence->getSchool() === $this) {
                $absence->setSchool(null);
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
            $rating->setSchool($this);
        }

        return $this;
    }

    public function removeRating(Ratings $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getSchool() === $this) {
                $rating->setSchool(null);
            }
        }

        return $this;
    }
}
