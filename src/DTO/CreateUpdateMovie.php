<?php

namespace App\DTO;

use App\Validator\GenreDoesExist;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUpdateMovie
{
    #[Groups(['create', 'update'])]
    #[Assert\NotBlank(message: 'Name darf nicht leer sein.', groups: ['create'])]
    public ?string $name = null;

    #[Groups(['create', 'update'])]
    public ?string $description = null;

    #[Groups(['create', 'update'])]
    #[Assert\NotBlank(message: 'Genre darf nicht leer sein.', groups: ['create'])]
    #[GenreDoesExist]
    public ?int $genre = null;

    #[Groups(['create', 'update'])]
    #[Assert\NotBlank(message: 'Agerest darf nicht leer sein.', groups: ['create'])]
    #[Assert\Positive(message: 'Es muss eine positive Zahl sein.', groups: ['create'])]
    public ?int $agerest = null;

    #[Groups(['create', 'update'])]
    #[Assert\NotBlank(message: 'Rating darf nicht leer sein.', groups: ['create'])]
    #[Assert\Positive(message: 'Es muss eine positive Zahl sein.', groups: ['create'])]
    public ?int $rating = null;
}
