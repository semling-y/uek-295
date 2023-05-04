<?php

namespace App\DTO\Mapper;

class ShowGenre extends BaseMapper
{

    public function mapEntityToDTO(object $entity) : object
    {
        $dto = new ShowGenre;
        $dto->genre = $entity->getGenre();

        return $dto;
    }

}