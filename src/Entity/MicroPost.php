<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 * @ORM\Table(name="micro_post")
 */
class MicroPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="text", type="string", length=280)
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     */
    private $text;

    /**
     * @var DateTime
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return MicroPost
     */
    public function setText(string $text): MicroPost
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getTime(): DateTime
    {
        return $this->time;
    }

    /**
     * @param DateTime $time
     * @return MicroPost
     */
    public function setTime(DateTime $time): MicroPost
    {
        $this->time = $time;
        return $this;
    }

}
