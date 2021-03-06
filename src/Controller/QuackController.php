<?php

namespace App\Controller;

use App\Entity\Quack;
use App\Form\QuackType;
use App\Repository\QuackRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

/**
 * @Route("/quack")
 */
class QuackController extends AbstractController
{
    /**
     * @Route("/", name="quack_index", methods={"GET"})
     * @Route("/tag/{tag}", name="quack_tags", methods={"GET"})
     */
    public function index(QuackRepository $quackRepository, $tag = null): Response
    {
        if ($tag) {
            $quacks = $quackRepository->findByTag($tag);
        } else {
            $quacks = $quackRepository->findBy(['parent' => null]);
        }
        return $this->render('quack/index.html.twig', [
            'quacks' => $quacks,

        ]);
    }

    /**
     * @Route("/new", name="quack_new", methods={"GET","POST"})
     * @Route("/{parent}/comment/new", name="quack_new_comment", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader, ?Quack $parent): Response
    {
        $quack = new Quack();
        $quack->setParent($parent);
//        dump($quack, $parent);
        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quack->setAuthor($this->getUser());
            //Uploader Image
            $file = $quack->getImageFile();
            if ($file == !null) {
                $fileName = $fileUploader->upload($file);
                $quack->setImage('/images/' . $fileName);
            }
            $quack->setCreatedAt(new \DateTime('now', (new \DateTimeZone('Europe/Paris'))));

            //flush to BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quack);
            $entityManager->flush();

            return $this->redirectToRoute('quack_index');
        }

        return $this->render('quack/new.html.twig', [
            'quack' => $quack,
            'form' => $form->createView(),
            "parent" => $parent,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="quack_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Quack $quack, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted('quack_edit', $quack);

        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quack->setCreatedAt(new \DateTime('now', (new \DateTimeZone('Europe/Paris'))));
            //Uploader Image

            $file = $quack->getImageFile();
            if ($file == !null) {
//            dump($file);
                $fileName = $fileUploader->upload($file);
                $quack->setImage('/images/' . $fileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quack_index');
        }
        // keep in mind that this will call all registered security voters
//        if (false === $this->get('security.context')->isGranted('view', $quack)) {
//            throw new AccessDeniedException('Unauthorized access!');
//        }

        return $this->render('quack/edit.html.twig', [
            'quack' => $quack,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quack_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Quack $quack): Response
    {
        $this->denyAccessUnlessGranted('quack_edit', $quack);

        if ($this->isCsrfTokenValid('delete' . $quack->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quack);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quack_index');
    }

    /**
     * @Route("/search", name="quack_search", methods={"GET"})
     */
    public function searchResult(Request $request, QuackRepository $quackRepository): Response
    {
        return $this->render('quack/index.html.twig', [
            'quacks' => $quackRepository->findBySearchBar($request->query->get('mysearch')),
        ]);

    }

    /**
     * @Route("/{id}", name="quack_show", methods={"GET"})
     */
    public function show(Quack $quack): Response
    {
        return $this->render('quack/show.html.twig', [
            'quack' => $quack,
        ]);
    }
}
