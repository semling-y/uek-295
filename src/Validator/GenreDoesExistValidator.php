<?php

namespace App\Validator;

use App\Repository\GenreRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class GenreDoesExistValidator extends ConstraintValidator
{
    public function __construct(private GenreRepository $repository)
    {
    }

    public function validate($idGenre, Constraint $constraint)
    {
        $genre = $this->repository->find($idGenre);

        if (!$genre) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ genreId }}', $idGenre)
                ->addViolation();
        }
    }
}
