<?php

namespace App\DTO\Mapper;

use App\DTO\ShowMovie;

class ShowMovieMapper extends BaseMapper
{
    public function mapEntityToDTO(object $entity) : object
    {
        $dto = new ShowMovie();
        $dto->name = $entity->getName();
        $dto->description = $entity->getDescription();
        $dto->agerest = $entity->getAgerest();
        $dto->genre_id = $entity->getGenre()->getId();
        $dto->rating = $entity->getRating();

        return $dto;
    }
}