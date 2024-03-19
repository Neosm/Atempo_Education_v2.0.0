<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $lastname = null;

    #[ORM\Column(length: 100)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 10)]
    private ?string $zipcode = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?bool $is_verified = null;

    #[ORM\Column]
    private ?bool $is_online = null;

    #[ORM\Column(length: 255)]
    private ?string $thumbnail = null;

    #[ORM\Column(length: 10)]
    private ?string $phone_number = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_of_birth = null;

    #[ORM\Column(length: 5)]
    private ?string $area_code = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Schools $school = null;

    #[ORM\ManyToMany(targetEntity: Programs::class, mappedBy: 'users')]
    private Collection $programs;

    #[ORM\ManyToMany(targetEntity: Lessons::class, mappedBy: 'users')]
    private Collection $lessons;

    #[ORM\OneToMany(targetEntity: Posts::class, mappedBy: 'author')]
    private Collection $posts;

    #[ORM\ManyToMany(targetEntity: Disciplines::class, mappedBy: 'teachers')]
    private Collection $disciplines;

    #[ORM\ManyToOne(inversedBy: 'students')]
    private ?StudentClasses $studentClasses = null;

    #[ORM\ManyToMany(targetEntity: Evaluations::class, mappedBy: 'students')]
    private Collection $evaluations;

    #[ORM\OneToMany(targetEntity: Absences::class, mappedBy: 'student')]
    private Collection $absences;

    #[ORM\OneToMany(targetEntity: Delays::class, mappedBy: 'student')]
    private Collection $delays;

    #[ORM\OneToMany(targetEntity: Ratings::class, mappedBy: 'student')]
    private Collection $ratings;

    #[ORM\OneToMany(targetEntity: Courses::class, mappedBy: 'teacher')]
    private Collection $coursesteacher;

    #[ORM\ManyToMany(targetEntity: Courses::class, mappedBy: 'students')]
    private Collection $courses;

    public function __construct()
    {
        $this->programs = new ArrayCollection();
        $this->lessons = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->disciplines = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->absences = new ArrayCollection();
        $this->delays = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->courses = new ArrayCollection();
        $this->coursesteacher = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): static
    {
        $this->zipcode = $zipcode;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): static
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    public function isIsOnline(): ?bool
    {
        return $this->is_online;
    }

    public function setIsOnline(bool $is_online): static
    {
        $this->is_online = $is_online;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): static
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(\DateTimeInterface $date_of_birth): static
    {
        $this->date_of_birth = $date_of_birth;

        return $this;
    }

    public function getAreaCode(): ?string
    {
        return $this->area_code;
    }

    public function setAreaCode(string $area_code): static
    {
        $this->area_code = $area_code;

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
            $program->addUser($this);
        }

        return $this;
    }

    public function removeProgram(Programs $program): static
    {
        if ($this->programs->removeElement($program)) {
            $program->removeUser($this);
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
            $lesson->addUser($this);
        }

        return $this;
    }

    public function removeLesson(Lessons $lesson): static
    {
        if ($this->lessons->removeElement($lesson)) {
            $lesson->removeUser($this);
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
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Posts $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
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
            $discipline->addTeacher($this);
        }

        return $this;
    }

    public function removeDiscipline(Disciplines $discipline): static
    {
        if ($this->disciplines->removeElement($discipline)) {
            $discipline->removeTeacher($this);
        }

        return $this;
    }

    public function getStudentClasses(): ?StudentClasses
    {
        return $this->studentClasses;
    }

    public function setStudentClasses(?StudentClasses $studentClasses): static
    {
        $this->studentClasses = $studentClasses;

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
            $evaluation->addStudent($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluations $evaluation): static
    {
        if ($this->evaluations->removeElement($evaluation)) {
            $evaluation->removeStudent($this);
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
            $absence->setStudent($this);
        }

        return $this;
    }

    public function removeAbsence(Absences $absence): static
    {
        if ($this->absences->removeElement($absence)) {
            // set the owning side to null (unless already changed)
            if ($absence->getStudent() === $this) {
                $absence->setStudent(null);
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
            $delay->setStudent($this);
        }

        return $this;
    }

    public function removeDelay(Delays $delay): static
    {
        if ($this->delays->removeElement($delay)) {
            // set the owning side to null (unless already changed)
            if ($delay->getStudent() === $this) {
                $delay->setStudent(null);
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
            $rating->setStudent($this);
        }

        return $this;
    }

    public function removeRating(Ratings $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getStudent() === $this) {
                $rating->setStudent(null);
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
            $course->addStudent($this);
        }

        return $this;
    }

    public function removeCourse(Courses $course): static
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            $course->removeStudent($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getCoursesteacher(): Collection
    {
        return $this->coursesteacher;
    }

    public function addCoursesteacher(Courses $coursesteacher): self
    {
        if (!$this->coursesteacher->contains($coursesteacher)) {
            $this->coursesteacher->add($coursesteacher);
            $coursesteacher->setTeacher($this);
        }

        return $this;
    }

    public function removeCoursesteacher(Courses $coursesteacher): self
    {
        if ($this->coursesteacher->removeElement($coursesteacher)) {
            // set the owning side to null (unless already changed)
            if ($coursesteacher->getTeacher() === $this) {
                $coursesteacher->setTeacher(null);
            }
        }

        return $this;
    }
}
