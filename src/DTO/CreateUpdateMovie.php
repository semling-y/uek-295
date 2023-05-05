<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUpdateMovie
{

    #[Assert\NotBlank(message: "Name darf nicht leer sein.", groups: ["create"])]
    public ?string $name = null;

    public ?string $description= null;

    #[Assert\NotBlank(message: "Genre darf nicht leer sein.", groups: ["create"])]
    public ?int $genre = null;

    #[Assert\NotBlank(message: "Agerest darf nicht leer sein.", groups: ["create"])]
    #[Assert\Positive(message: "Es muss eine positive Zahl sein.", groups: ["create"])]
    public ?int $agerest = null;

    #[Assert\NotBlank(message: "Rating darf nicht leer sein.", groups: ["create"])]
    #[Assert\Positive(message: "Es muss eine positive Zahl sein.", groups: ["create"])]
    public ?int $rating = null;
}