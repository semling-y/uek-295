<?php

namespace App\Controller;

use App\DTO\CreateUpdateMovie;
use App\DTO\Mapper\BaseMapper;
use App\DTO\Mapper\IMapper;
use App\Entity\Movie;
use App\FilterGenre;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api", name: "api_")]
class DataController extends AbstractController
{
    public function __construct(private SerializerInterface $serializer, private  MovieRepository $repository, private GenreRepository $genreRepository, private CreateUpdateMovie $mapper){

    }

    #[Post("/data", name: "app_data_create")]
    public function createMovie(Request $request){
        $dto = $this->serializer->deserialize($request->getContent(), CreateUpdateMovie::class, "json");
        $genre = $this->genreRepository->find($dto->genre);

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
                FilterGenre::class,
                'json'
            );
        }
        catch (\Exception $ex) {
            $dtoFilter = new FilterGenre();
        }


        $dtoAllMovie = $this->repository->filterAll($dtoFilter);

        return (new JsonResponse())->setContent(
            $this->serializer->serialize(
                $this->mapper->mapEntitiesToDTOS($dtoAllMovie), 'json')
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
