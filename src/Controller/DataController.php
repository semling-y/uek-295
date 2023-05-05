<?php

namespace App\Controller;

use App\DTO\CreateUpdateMovie;
use App\DTO\FilterMovie;
use App\DTO\Mapper\ShowMovieMapper;
use App\Entity\Movie;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api", name: "api_")]
class DataController extends AbstractController
{
    public function __construct(private ShowMovieMapper $mapper, private SerializerInterface $serializer, private  MovieRepository $repository, private GenreRepository $genreRepository, private ValidatorInterface $validator){

    }

    #[Post("/data", name: "app_data_create")]
    public function createMovie(Request $request){
        $dto = $this->serializer->deserialize($request->getContent(), CreateUpdateMovie::class, "json");
        $genre = $this->genreRepository->find($dto->genre);


        $errors = $this->validator->validate($dto, groups: ["create"]);

        if($errors->count() > 0){
            $errorsStringArray = [];
            foreach ($errors as $error){
                $errorsStringArray = $error->getMessage();
            }
            return $this->json($errorsStringArray, status: 400);
        }


        $entity = new Movie();

        $entity->setName($dto->name);
        $entity->setDescription($dto->description);
        $entity->setGenre($genre);
        $entity->setAgerest($dto->agerest);
        $entity->setRating($dto->rating);

        $this->repository->save($entity, true);

        return $this->json("Film wurde erstellt.");
    }


    #[Get('/data', name: 'app_data_get')]
    public function getmovie(Request $request): Response{
        $dtoFilter = null;

        try {
            $dtoFilter = $this->serializer->deserialize(
                $request->getContent(),
                FilterMovie::class,
                'json'
            );
        }
        catch (\Exception $ex) {
            $dtoFilter = new FilterMovie();
        }


        $dtoAllMovie = $this->repository->filterAll($dtoFilter);

        return (new JsonResponse())->setContent(
            $this->serializer->serialize(
                $this->mapper->mapEntitiesToDTOS($dtoAllMovie), 'json'
            )
        );
    }


    #[Route('/data/{id}', name: 'app_data_update', methods: ['PUT'])]
    public function updateMovie(Request $request, int $id): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreateUpdateMovie::class, 'json');

        $movie = $this->repository->find($id);

        if (!$movie) {
            return $this->json("Dieser Film wurde nicht gefunden.");
        }

        $genre = $this->genreRepository->find($dto->genre);



        $movie->setName($dto->name);
        $movie->setDescription($dto->description);
        $movie->setGenre($genre);
        $movie->setAgerest($dto->agerest);
        $movie->setRating($dto->rating);

        $this->repository->save($movie, true);

        return (new JsonResponse())->setContent(
            $this->serializer->serialize(
                $this->mapper->mapEntityToDTO($movie),
                'json'
            )
        );
    }



    #[Delete('/data', name: 'app_data_delete')]
    public function deleteMovie(): Response
    {
        return $this->render('data/index.html.twig', [
            'controller_name' => 'DataController',
        ]);
    }
}
