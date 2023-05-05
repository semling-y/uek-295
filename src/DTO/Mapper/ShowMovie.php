<?php

namespace App\DTO\Mapper;

class ShowMovie
{

    public function mapEntityToDTO(object $entity) : object
    {
        $mapper = new ShowGenre();

        $dto = new ShowMovie();
        $dto->genre = $entity->getGenre();

        $dto->movie = $mapper->mapEntitiesTODTOS($entity->getGenre());

        return $dto;
    }

}