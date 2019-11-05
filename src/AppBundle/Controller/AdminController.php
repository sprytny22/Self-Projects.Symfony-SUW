<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Download;
use AppBundle\Entity\User;
use AppBundle\Form\SubFileType;
use AppBundle\Entity\SubFile;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class AdminController extends Controller
{
    public function deleteFileAction($id) {
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository(SubFile::class)->find($id);
        $namef = $file->getNameFile();

        $this->addFlash('success','Removed '.$namef.' from database.');

        $em->remove($file);
        $em->flush();

        return $this->redirectToRoute('uploadfile');
    }

    public function acceptAction(){
        $em = $this->getDoctrine()->getManager();
        $students = $em->getRepository(User::class)->findBy(['enabled' => '0']);

        return $this->render('default/accept.html.twig', [
            'users' => $students,
        ]);
    }

    public function acceptedAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository(User::class)->find($id);

        $student->setEnabled(true);

        $em->merge($student);
        $em->flush();

        $this->addFlash('success_accepted_user','Zaakceptowano!');

        return $this->redirectToRoute('accept');
    }

    public function changePasswordAction(Request $request) {
        $form = $this->createFormBuilder()
            ->add('username', TextType::class, ['label' => 'Numer albumu']) //TODO: valid
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array(
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'autocomplete' => 'new-password',
                    ),
                ),
                'first_options' => array('label' => 'form.new_password'),
                'second_options' => array('label' => 'form.new_password_confirmation'),
            ))
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            $ind = $form['username']->getData();
            $passwd = $form['plainPassword']->getData();

            $userManager = $this->container->get('fos_user.user_manager');
            $um = $userManager->findUserBy(array('indNumber' => $ind));

            $um->setPlainPassword($passwd); 

            $userManager->updateUser($um);
        }

        return $this->render('default/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function statisticsAction(Request $request) {

        $files = $this->getDoctrine()->getRepository(SubFile::class)->getNumberOfFiles();
        $students = $this->getDoctrine()->getRepository(User::class)->getNumberOfUsers();
        $downloads = $this->getDoctrine()->getRepository(User::class)->getNumberOfDownloads();

        $files1 = $files[0][1];
        $students1 = $students[0][1];
        $downloads1 = $downloads[0][1];

        return $this->render('default/stat.html.twig', [
            'downloads' => $downloads1,
            'files' => $files1,
            'students' => $students1,
        ]);
    }

    public function historyAction(Request $request) { //TODO: security

        $em = $this->getDoctrine()->getManager();
        $downloads = $em->getRepository(Download::class)->findAll();

        return $this->render('default/history.html.twig', [
            'downloads' => $downloads,
        ]);
    }
}
