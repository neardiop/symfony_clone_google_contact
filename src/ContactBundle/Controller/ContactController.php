<?php

namespace ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ContactBundle\Entity\Contact;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContactController extends Controller
{
    /**
     * @Route("/contacts",name="acceuil")
     */
    public function createAction(Request $request)
    {
        $contact = new Contact();

        $form = $this->createFormBuilder($contact)
            ->add('nom',TextType::class, array('label' => 'Nom'))
            ->add('prenom',TextType::class, array('label' => 'Prenom'))
            ->add('telephone',TextType::class, array('label' => 'Telephone'))
            ->add('email',EmailType::class, array('label' => 'Email'))
            ->add('imgUrl',TextType::class, array('label' => 'imgUrl'))
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Statut Contact' => null,
                    'Autorisé' => true,
                    'Bloqué' => false,
                ],
            ])
            ->add('create',SubmitType::class, array('label' => 'Enregistrer'))
            ->getForm();

        if ($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()){

                $nom = $form['nom']->getData();
                $prenom = $form['prenom']->getData();
                $email = $form['email']->getData();
                $telephone = $form['telephone']->getData();
                $imgUrl = $form['imgUrl']->getData();
                $status = $form['status']->getData();

                $contact->setNom($nom);
                $contact->setPrenom($prenom);
                $contact->setEmail($email);
                $contact->setTelephone($telephone);
                $contact->setImgUrl($imgUrl);
                $contact->setStatus($status);

                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();

                $repository = $this->getDoctrine()->getManager()->getRepository('ContactBundle:Contact');
                $contacts = $repository->findAll();

                return $this->render('ContactBundle:Default:contacts.html.twig',array('form'=> $form->createView(),'contacts' =>$contacts));
            }
        }

        $repository = $this->getDoctrine()->getManager()->getRepository('ContactBundle:Contact');
        $contacts = $repository->findAll();

        return $this->render('ContactBundle:Default:contacts.html.twig',array('form'=> $form->createView(),'contacts' =>$contacts ));

    }

    /**
     * @Route("/contacts/delete/{id}",name="delete")
     */
    public function deleteAction(Contact $contact){
        $em = $this->getDoctrine()->getManager();
        $em->remove($contact);
        $em->flush();

        $form = $this->createFormBuilder($contact)
            ->add('nom',TextType::class, array('label' => 'Nom'))
            ->add('prenom',TextType::class, array('label' => 'Prenom'))
            ->add('telephone',TextType::class, array('label' => 'Telephone'))
            ->add('email',EmailType::class, array('label' => 'Email'))
            ->add('imgUrl',TextType::class, array('label' => 'imgUrl'))
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Statut Contact' => null,
                    'Autorisé' => true,
                    'Bloqué' => false,
                ],
            ])
            ->add('create',SubmitType::class, array('label' => 'Enregistrer'))
            ->getForm();

        $repository = $this->getDoctrine()->getManager()->getRepository('ContactBundle:Contact');
        $contacts = $repository->findAll();

        return $this->redirectToRoute("acceuil",array('form'=> $form->createView(),'contacts' =>$contacts ));

    }

    /**
     * @Route("/contacts/search/{prenom}",name="search")
     */
    public function searchAction($prenom, Request $request){

        $repository = $this->getDoctrine()->getManager()->getRepository('ContactBundle:Contact');
        $contact = $repository->findBy(['prenom' => $prenom]);
        $form = $this->createFormBuilder($contact)
            ->add('nom',TextType::class, array('label' => 'Nom'))
            ->add('prenom',TextType::class, array('label' => 'Prenom'))
            ->add('telephone',TextType::class, array('label' => 'Telephone'))
            ->add('email',EmailType::class, array('label' => 'Email'))
            ->add('imgUrl',TextType::class, array('label' => 'imgUrl'))
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Statut Contact' => null,
                    'Autorisé' => true,
                    'Bloqué' => false,
                ],
            ])
            ->add('create',SubmitType::class, array('label' => 'Enregistrer'))
            ->getForm();
        if ($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()){

                $nom = $form['nom']->getData();
                $prenom = $form['prenom']->getData();
                $email = $form['email']->getData();
                $telephone = $form['telephone']->getData();
                $imgUrl = $form['imgUrl']->getData();
                $status = $form['status']->getData();

                $contact->setNom($nom);
                $contact->setPrenom($prenom);
                $contact->setEmail($email);
                $contact->setTelephone($telephone);
                $contact->setImgUrl($imgUrl);
                $contact->setStatus($status);

                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();

                return $this->render('ContactBundle:Default:contacts.html.twig',array('form'=> $form->createView(),'contacts' =>$contact ));
            }
        }

        return $this->render('ContactBundle:Default:contacts.html.twig',array('form'=> $form->createView(),'contacts' =>$contact ));

    }

    /**
     * @Route("/contacts/view/{id}",name="display")
     */
    public function displayContact($id,Request $request){
        $repository = $this->getDoctrine()->getManager()->getRepository('ContactBundle:Contact');
        $contact = $repository->find($id);
        $form = $this->createFormBuilder($contact)
            ->add('nom',TextType::class, array('label' => 'Nom'))
            ->add('prenom',TextType::class, array('label' => 'Prenom'))
            ->add('telephone',TextType::class, array('label' => 'Telephone'))
            ->add('email',EmailType::class, array('label' => 'Email'))
            ->add('imgUrl',TextType::class, array('label' => 'imgUrl'))
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Statut Contact' => null,
                    'Autorisé' => true,
                    'Bloqué' => false,
                ],
            ])
            ->add('create',SubmitType::class, array('label' => 'Enregistrer'))
            ->getForm();
        if ($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()){

                $nom = $form['nom']->getData();
                $prenom = $form['prenom']->getData();
                $email = $form['email']->getData();
                $telephone = $form['telephone']->getData();
                $imgUrl = $form['imgUrl']->getData();
                $status = $form['status']->getData();

                $contact->setNom($nom);
                $contact->setPrenom($prenom);
                $contact->setEmail($email);
                $contact->setTelephone($telephone);
                $contact->setImgUrl($imgUrl);
                $contact->setStatus($status);

                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();

                return $this->render('ContactBundle:Default:displayContact.html.twig',array('form'=> $form->createView(),'contact' =>$contact ));
            }
        }

        return $this->render('ContactBundle:Default:displayContact.html.twig',array('form'=> $form->createView(),'contact' =>$contact ));
    }


    /**
     * @Route("/contacts/bloques",name="bloquer")
     */
    public function contactBloquerAction(Request $request)
    {
        $contact = new Contact();

        $form = $this->createFormBuilder($contact)
            ->add('nom',TextType::class, array('label' => 'Nom'))
            ->add('prenom',TextType::class, array('label' => 'Prenom'))
            ->add('telephone',TextType::class, array('label' => 'Telephone'))
            ->add('email',EmailType::class, array('label' => 'Email'))
            ->add('imgUrl',TextType::class, array('label' => 'imgUrl'))
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Statut Contact' => null,
                    'Autorisé' => true,
                    'Bloqué' => false,
                ],
            ])
            ->add('create',SubmitType::class, array('label' => 'Enregistrer'))
            ->getForm();

        if ($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()){

                $nom = $form['nom']->getData();
                $prenom = $form['prenom']->getData();
                $email = $form['email']->getData();
                $telephone = $form['telephone']->getData();
                $imgUrl = $form['imgUrl']->getData();
                $status = $form['status']->getData();

                $contact->setNom($nom);
                $contact->setPrenom($prenom);
                $contact->setEmail($email);
                $contact->setTelephone($telephone);
                $contact->setImgUrl($imgUrl);
                $contact->setStatus($status);

                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();

                $repository = $this->getDoctrine()->getManager()->getRepository('ContactBundle:Contact');
                $contacts = $repository->findAll();

                return $this->render('ContactBundle:Default:contacts.html.twig',array('form'=> $form->createView(),'contacts' =>$contacts));
            }
        }
        $repository = $this->getDoctrine()->getManager()->getRepository('ContactBundle:Contact');
        $contacts = $repository->findBy(['status' => 0]);
        return $this->render('ContactBundle:Default:contacts.html.twig',array('form'=> $form->createView(),'contacts' =>$contacts ));
    }


    /**
     * @Route("/contacts/autorises",name="autoriser")
     */
    public function contactAutoriserAction(Request $request)
    {
        $contact = new Contact();

        $form = $this->createFormBuilder($contact)
            ->add('nom',TextType::class, array('label' => 'Nom'))
            ->add('prenom',TextType::class, array('label' => 'Prenom'))
            ->add('telephone',TextType::class, array('label' => 'Telephone'))
            ->add('email',EmailType::class, array('label' => 'Email'))
            ->add('imgUrl',TextType::class, array('label' => 'imgUrl'))
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Statut Contact' => null,
                    'Autorisé' => true,
                    'Bloqué' => false,
                ],
            ])
            ->add('create',SubmitType::class, array('label' => 'Enregistrer'))
            ->getForm();

        if ($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()){

                $nom = $form['nom']->getData();
                $prenom = $form['prenom']->getData();
                $email = $form['email']->getData();
                $telephone = $form['telephone']->getData();
                $imgUrl = $form['imgUrl']->getData();
                $status = $form['status']->getData();

                $contact->setNom($nom);
                $contact->setPrenom($prenom);
                $contact->setEmail($email);
                $contact->setTelephone($telephone);
                $contact->setImgUrl($imgUrl);
                $contact->setStatus($status);

                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();

                $repository = $this->getDoctrine()->getManager()->getRepository('ContactBundle:Contact');
                $contacts = $repository->findAll();

                return $this->render('ContactBundle:Default:contacts.html.twig',array('form'=> $form->createView(),'contacts' =>$contacts));
            }
        }
        $repository = $this->getDoctrine()->getManager()->getRepository('ContactBundle:Contact');
        $contacts = $repository->findBy(['status' => 1]);
        return $this->render('ContactBundle:Default:contacts.html.twig',array('form'=> $form->createView(),'contacts' =>$contacts ));
    }


}

