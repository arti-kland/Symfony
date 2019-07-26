<?php


namespace App\Controller\API;


use App\Entity\Quack;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Repository\QuackRepository;

class ApiController extends AbstractController
{
    //////////////////// QUACK /////////////////////////////////////////////
    /**
     * @Route("/api/quack", name="api_show", methods={"GET"})
     */
    public function showAll(QuackRepository $quackRepository)
    {
        $data = $quackRepository->findAll();
        $response = new JsonResponse($data);
        return $response;
    }

    /**
     * @Route("/api/quack/{id}", name="api_id_image", methods={"GET"})
     */
    public function showIdImage(QuackRepository $quackRepository, $id)
    {
        $datas = $quackRepository->findBy(array('id' => $id));
        $result = [];
        foreach ($datas as $data) {
            $result[] = $data->monTableauLight();
        }
        $response = new JsonResponse($result);
        return $response;
    }

}