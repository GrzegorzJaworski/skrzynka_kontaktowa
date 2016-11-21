<?php

namespace ContactBoxBundle\Controller;

use ContactBoxBundle\Entity\Person;
use ContactBoxBundle\Entity\Address;
use ContactBoxBundle\Entity\Phone;
use ContactBoxBundle\Entity\Email;
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
//                ->add('address')
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
        $em = $this->getDoctrine()->getManager();
        $em->remove($person);
        $em->flush();
        return new Response('<html><body>Osoba zostala usunieta!</body></html>');
    }

    /**
     * @Route("/{id}/deleteAddress", name="delete_address")
     * @Template() 
     */
    public function deleteAddressAction(Request $request, $id) {
        $repository = $this->getDoctrine()->getRepository('ContactBoxBundle:Address');
        $address = $repository->find($id);
        
        if (!$address) {
            return new Response('<html><body>Nie ma takiego adresu!</body></html>');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($address);
        $em->flush();
        return $this->redirectToRoute('person_show', array('id' => $address->getPerson()));
    }
    
    /**
     * @Route("/{id}/deletePhone", name="delete_phone")
     * @Template() 
     */
    public function deletePhoneAction(Request $request, $id) {
        $repository = $this->getDoctrine()->getRepository('ContactBoxBundle:Phone');
        $phone = $repository->find($id);
        
        if (!$phone) {
            return new Response('<html><body>Nie ma takiego telefonu!</body></html>');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($phone);
        $em->flush();
        return $this->redirectToRoute('person_show', array('id' => $phone->getPerson()));
    }
    /**
     * @Route("/{id}/deleteEmail", name="delete_email")
     * @Template() 
     */
    public function deleteEmailAction(Request $request, $id) {
        $repository = $this->getDoctrine()->getRepository('ContactBoxBundle:Email');
        $email = $repository->find($id);
        
        if (!$email) {
            return new Response('<html><body>Nie ma takiego maila!</body></html>');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($email);
        $em->flush();
        return $this->redirectToRoute('person_show', array('id' => $email->getPerson()));
    }

    /**
     * Lists all person entities.
     *
     * @Route("/", name="person_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                "SELECT person FROM ContactBoxBundle:Person person ORDER BY person.name ASC"
        );

        $people = $query->getResult();

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

        $address = new Address();
        $formAddress = $this->createFormBuilder($address)
                ->setAction($this->generateUrl('addMail', array('id' => $id)))
                ->add('city', 'text')
                ->add('street', 'text')
                ->add('houseNo', 'textarea')
                ->add('apartmentNo', 'number')
                ->add('dodaj', 'submit')
                ->getForm();

        $phone = new Phone();
        $formPhone = $this->createFormBuilder($phone)
                ->setAction($this->generateUrl('addPhone', array('id' => $id)))
                ->add('number', 'number')
                ->add('type', 'text')
                ->add('dodaj', 'submit')
                ->getForm();

        $email = new Email();
        $formEmail = $this->createFormBuilder($email)
                ->setAction($this->generateUrl('addEmail', array('id' => $id)))
                ->add('address', 'text')
                ->add('type', 'text')
                ->add('dodaj', 'submit')
                ->getForm();

        return $this->render('person/show.html.twig', array(
                    'person' => $person, 'formAddress' => $formAddress->createView(),
                    'formPhone' => $formPhone->createView(), 'formEmail' => $formEmail->createView(),
        ));
    }

    /**
     * @Route("/{id}/addMail", name="addMail")
     * 
     */
    public function addAddressAction(Request $request, $id) {
        $repository = $this->getDoctrine()->getRepository('ContactBoxBundle:Person');
        $person = $repository->findOneById($id);

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
            $address->setPerson($person);

            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();
            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }
        return new Response('<html><body>Brak reqesta!</body></html>');
    }

    /**
     *
     * @Route("/{id}/addPhone", name="addPhone")
     * 
     */
    public function addPhoneAction(Request $request, $id) {
        $repository = $this->getDoctrine()->getRepository('ContactBoxBundle:Person');
        $person = $repository->findOneById($id);

        $phone = new Phone();
        $form = $this->createFormBuilder($phone)
                ->add('number', 'number')
                ->add('type', 'text')
                ->add('dodaj', 'submit')
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $address = $form->getData();
            $address->setPerson($person);

            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();
            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }
        return new Response('<html><body>Brak reqesta!</body></html>');
    }

    /**
     *
     * @Route("/{id}/addEmail", name="addEmail")
     * 
     */
    public function addEmailAction(Request $request, $id) {
        $repository = $this->getDoctrine()->getRepository('ContactBoxBundle:Person');
        $person = $repository->findOneById($id);

        $email = new Email();
        $form = $this->createFormBuilder($email)
                ->add('address', 'text')
                ->add('type', 'text')
                ->add('dodaj', 'submit')
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $email = $form->getData();
            $email->setPerson($person);

            $em = $this->getDoctrine()->getManager();
            $em->persist($email);
            $em->flush();
            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }
        return new Response('<html><body>Brak reqesta!</body></html>');
    }

}
