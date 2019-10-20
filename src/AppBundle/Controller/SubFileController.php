<?php

namespace AppBundle\Controller;

use AppBundle\Form\SubFileType;
use AppBundle\Entity\SubFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubFileController extends Controller
{
    /**
     * @Route("/Upload", name="uploadfile")
     */
    public function addAction(Request $request)
    {
        $subfile = new SubFile();

        $form = $this->createForm(SubFileType::class, $subfile);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $brochureFile = $form['brochure']->getData();

            if($brochureFile) {

                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = 'filename';
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();


                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $subfile->setBrochureFileName($newFilename);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($subfile);
                $entityManager->flush();
            }
        }


        // replace this example code with whatever you need
        return $this->render('default/subfile.html.twig', [
        'form' => $form->createView(),
    ]);
    }
}
