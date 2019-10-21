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

        $form = $this->createForm(SubFileType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $brochureFile = $form['brochure']->getData();
            $nameFile = $form['namefile']->getData();
            $subjectName =  $form['subjectname']->getData();

            if($brochureFile) {

                $brochureFileName = $nameFile.'-'.uniqid().'.'.$brochureFile->guessExtension();
                $extensionFile = $brochureFile->guessExtension();
                $nowTime = new \DateTime();

                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $brochureFileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $subfile->setNameFile($nameFile);
                $subfile->setExtensionFile($extensionFile);
                $subfile->setCreatedAt($nowTime);
                $subfile->setSubjectName($subjectName);
                $subfile->setBrochureFileName($brochureFileName);

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
