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
     * @Route("/wyklady", name="uploadfile")
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
            //$typeName = $form['typename']->getData();

            if($brochureFile) {

                $brochureFileName = $nameFile.'-'.uniqid().'.'.$brochureFile->guessExtension();
                $extensionFile = $brochureFile->guessExtension();
                $time = new \DateTime();
                $nowTime = $time->format('d-m-Y');

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
                // $subfile->setTypeFile($typeName);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($subfile);
                $entityManager->flush();
            }
        }

        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository(SubFile::class)->findAll();


        // replace this example code with whatever you need
        return $this->render('default/subfile.html.twig', [
            'form' => $form->createView(),
            'files' => $file,
        ]);
    }

    /**
     * @Route("/wyklady/delete/{id}", name="deletefile")
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository(SubFile::class)->find($id);
        $namef = $file->getNameFile();

        $this->addFlash('success','Removed '.$namef.' from database.');

        $em->remove($file);
        $em->flush();

        return $this->redirectToRoute('uploadfile');
    }

    /**
     * @Route("/wyklady/get/{id}", name="getfile")
     */

    public function getFileAction($id) {
        $em = $this->getDoctrine()->getManager();
        $fileID = $em->getRepository(SubFile::class)->find($id);

        $fullFileName = $fileID->getBrochureFileName();

        $pdfPath = $this->getParameter('brochures_directory').'/'.$fullFileName;

        return $this->file($pdfPath);
    }
}
