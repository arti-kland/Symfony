<?php

namespace App\Controller;

use App\Entity\DuckDuck;
use App\Form\DuckDuckType;
use App\Repository\DuckDuckRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function new(Request $request, FileUploader $fileUploader): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $duckDuck = new DuckDuck();
        $form = $this->createForm(DuckDuckType::class, $duckDuck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Uploader Image
            $file = $duckDuck->getImageFile();
            $fileName = $fileUploader->upload($file);
            $duckDuck->setImage('/images/' . $fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($duckDuck);
            $entityManager->flush();

            return $this->redirectToRoute('duck_duck_index');


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
        $this->denyAccessUnlessGranted('duck_edit', $duckDuck);

        return $this->render('duck_duck/show.html.twig', [
            'duck_duck' => $duckDuck,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="duck_duck_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DuckDuck $duckDuck, UserPasswordEncoderInterface $passwordEncoder, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted('duck_edit', $duckDuck);

        $form = $this->createForm(DuckDuckType::class, $duckDuck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($form->get('currentPassword')->getData())) {

                //Uploader Image
                $file = $duckDuck->getImageFile();
                $fileName = $fileUploader->upload($file);
                $duckDuck->setImage('/images/' . $fileName);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash(
                    'info',
                    'Your changes were saved !'
                );
                $duckDuck->setImageFile(null);


            } else if ($passwordEncoder->isPasswordValid($this->getUser(), $form->get('currentPassword')->getData())) {
                $duckDuck->setPassword(
                    $passwordEncoder->encodePassword(
                        $duckDuck,
                        $form->get('plainPassword')->getData()
                    ));

                //Uploader Image
                $file = $duckDuck->getImageFile();
                $fileName = $fileUploader->upload($file);
                $duckDuck->setImage('/images/' . $fileName);

                $this->getDoctrine()->getManager()->flush();
                $this->addFlash(
                    'success',
                    'Your changes were saved !'

                );
                $duckDuck->setImageFile(null);

            } else {
                $this->addFlash(
                    'warning',
                    'invalid password !'
                );
            }

            return $this->redirectToRoute('duck_duck_index');
        }
        return $this->render('duck_duck/edit.html.twig', [
            'duck_duck' => $duckDuck,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="duck_duck_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DuckDuck $duckDuck): Response
    {
        $this->denyAccessUnlessGranted('duck_delete', $duckDuck);

        if ($this->isCsrfTokenValid('delete' . $duckDuck->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($duckDuck);
            $entityManager->flush();
        }

        return $this->redirectToRoute('duck_duck_index');
    }
}
