<?php

namespace ContactBoxBundle\Controller;

use ContactBoxBundle\Entity\Person;
use ContactBoxBundle\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Person controller.
 *
 * @Route("person")
 */
class PersonController extends Controller {

    /**
     * @Route("/new", name="new_person")
     * @Template() 
     */
    public function newPersonAction(Request $request) {
        $person = new Person();
        $form = $this->createFormBuilder($person)
                ->add('name', 'text')
                ->add('surname', 'text')
                ->add('description', 'textarea')
                ->add('dodaj', 'submit')
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $person = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/{id}/modify", name="modify_person")
     * @Template() 
     */
    public function modifyPersonAction(Request $request, $id) {
        $repository = $this->getDoctrine()->getRepository('ContactBoxBundle:Person');
        $person = $repository->find($id);
        $form = $this->createFormBuilder($person)
                ->add('name', 'text')
                ->add('surname', 'text')
                ->add('description', 'textarea')
                ->add('zmień', 'submit')
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $person = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            return $this->redirectToRoute('person_index');
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/{id}/delete", name="delete_person")
     * @Template() 
     */
    public function deletePersonAction(Request $request, $id) {
        $repository = $this->getDoctrine()->getRepository('ContactBoxBundle:Person');
        $person = $repository->find($id);
        if (!$person) {
            return new Response('<html><body>Nie ma takiej osoby!</body></html>');
        }
        $em->remove($post);
        $em->flush();
        return new Response('<html><body>Osoba zostala usunieta!</body></html>');
    }

    /**
     * Lists all person entities.
     *
     * @Route("/", name="person_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $people = $em->getRepository('ContactBoxBundle:Person')->findAll();

        return $this->render('person/index.html.twig', array(
                    'people' => $people,
        ));
    }

    /**
     * Finds and displays a person entity.
     *
     * @Route("/{id}", name="person_show")
     * 
     */
    public function showAction(Request $request, $id) {

        $repository = $this->getDoctrine()->getRepository('ContactBoxBundle:Person');
        $person = $repository->findOneById($id);
        
//    dump($person);
//    die();
        $address = new Address();
        $form = $this->createFormBuilder($address)
                ->add('city', 'text')
                ->add('street', 'text')
                ->add('houseNo', 'textarea')
                ->add('apartmentNo', 'number')
                ->add('dodaj', 'submit')
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $address = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            
            $em->flush();
            
            $person->setAddress($address);
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            
            $em->flush();
           
            
            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return $this->render('person/show.html.twig', array(
                    'person' => $person, 'form' => $form->createView(),
        ));
    }

}
