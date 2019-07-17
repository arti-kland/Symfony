<?php

namespace App\Controller;

use App\Entity\DuckDuck;
use App\Form\DuckDuckType;
use App\Repository\DuckDuckRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/duckduck")
 */
class DuckDuckController extends AbstractController
{
    /**
     * @Route("/", name="duck_duck_index", methods={"GET"})
     */
    public function index(DuckDuckRepository $duckDuckRepository): Response
    {
        return $this->render('duck_duck/index.html.twig', [
            'duck_ducks' => $duckDuckRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="duck_duck_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $duckDuck = new DuckDuck();
        $form = $this->createForm(DuckDuckType::class, $duckDuck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($duckDuck);
            $entityManager->flush();

                return $this->redirectToRoute('quack_index');


        }

        return $this->render('duck_duck/new.html.twig', [
            'duck_duck' => $duckDuck,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="duck_duck_show", methods={"GET"})
     */
    public function show(DuckDuck $duckDuck): Response
    {
        return $this->render('duck_duck/show.html.twig', [
            'duck_duck' => $duckDuck,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="duck_duck_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DuckDuck $duckDuck): Response
    {
        $form = $this->createForm(DuckDuckType::class, $duckDuck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('quack_index');

        }

        return $this->render('duck_duck/edit.html.twig', [
            'duck_duck' => $duckDuck,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="duck_duck_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DuckDuck $duckDuck): Response
    {
        if ($this->isCsrfTokenValid('delete'.$duckDuck->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($duckDuck);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quack_index');
    }
}
