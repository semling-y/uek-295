<?php

namespace App\Controller;

use App\DTO\CreateUpdateGenre;
use App\DTO\Mapper\BaseMapper;
use App\DTO\Mapper\ShowGenre;
use App\Entity\Genre;
use App\Repository\GenreRepository;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api", name: "api_")]
class GenreController extends AbstractController
{
    public function __construct(private SerializerInterface $serializer, private  GenreRepository $repository, private ShowGenre $mapper){

    }

    #[Get('/genre', name: 'app_genre_get')]
    public function getMovie(): Response
    {
        return $this->render('genre/index.html.twig', [
            'controller_name' => 'DataController',
        ]);
    }

    #[Post('/genre', name: 'app_genre_create')]
    public function createMovie(Request $request, GenreRepository $repository): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreateUpdateGenre::class, "json");

        $entity = new Genre();
        $entity->setGenre($dto->genre);

        $this->repository->save($entity, true);

        return (new JsonResponse())->setContent(
            $this->serializer->serialize(
                $this->mapper->mapEntityToDTO($entity), "json")
        );
    }

    #[Delete('/genre', name: 'app_genre_delete')]
    public function deleteMovie(): Response
    {
        return $this->render('data/index.html.twig', [
            'controller_name' => 'DataController',
        ]);
    }
}
