<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Download;
use AppBundle\Form\SubFileType;
use AppBundle\Entity\SubFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;


class UserController extends Controller
{
    public function fileAction(Request $request)
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


        return $this->render('default/subfile.html.twig', [
            'form' => $form->createView(),
            'files' => $file,
        ]);
    }

    public function getFileAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $file = $em->getRepository(SubFile::class)->find($id);
        $user = $this->getUser();
        $ip = $request->getClientIp();

        $fullFileName = $file->getBrochureFileName();

        $pdfPath = $this->getParameter('brochures_directory').'/'.$fullFileName;

        $download = new Download();
        $download->setUser($user);
        $download->setFile($file);
        $download->setIp($ip);

        $em->persist($download);
        $em->flush();

        return $this->file($pdfPath);
    }
}
