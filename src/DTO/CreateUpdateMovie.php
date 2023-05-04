<?php

namespace App\DTO;

class CreateUpdateMovie
{
    public ?string $name = null;
    public ?string $description= null;
    public ?int $genre = null;
    public ?int $agerest = null;
    public ?int $rating = null;
}