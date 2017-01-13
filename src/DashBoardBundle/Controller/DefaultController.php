<?php

namespace DashBoardBundle\Controller;

use DashBoardBundle\Entity\Search;
#use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use UserBundle\Form\UserUpdateType;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $search = new Search();
        $form = $this->createFormBuilder($search)->add('search', TextType::class, array('label' => 'Aliment'))->add('add', SubmitType::class, array('label' => 'Ajouter'))->getForm();
        $param["form_food"] = $form->createView();
        return $this->render('DashBoardBundle:Default:index.html.twig', $param);
    }

    public function searchAction(Request $request)
    {
        $search = new Search();
        $form = $this->createFormBuilder($search)->add('search', TextType::class)->add('proceed', SubmitType::class, array('label' => 'Rechercher'))->getForm();
        $form->handleRequest($request);
        $param["form_search"] = $form->createView();
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $data = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $friends = $em->createQuery("SELECT p FROM UserBundle:User p WHERE p.name LIKE '%".$data->getSearch()."%'")->getResult();
                $param["friendsSame"] = array();
                $param["friendsSameFamily"] = array();
                $param["friendsSameRace"] = array();
                $param["friendDifferents"] = array();
                /** @var User $friend */
                foreach ($friends as $friend)
                {
                    if ($friend->getId() != $this->getUser()->getId() && !$this->getUser()->getFriends()->contains($friend)) {
                        if ($friend->getFamille() == $this->getUser()->getFamille() && $friend->getRace() == $this->getUser()->getRace())
                            $param["friendsSame"][] = $friend;
                        elseif ($friend->getFamille() == $this->getUser()->getFamille())
                            $param["friendsSameFamily"][] = $friend;
                        elseif ($friend->getRace() == $this->getUser()->getRace())
                            $param["friendsSameRace"][] = $friend;
                        else
                            $param["friendDifferents"][] = $friend;
                    }
                }
            }
        }
        return $this->render("@DashBoard/search.html.twig", $param);
    }

    public function updateAction(Request $request)
    {
        $form = $this->createForm(UserUpdateType::class, $this->getUser());
        $form->handleRequest($request);
        $param["form_update"] = $form->createView();
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                /** @var User $data */
                $data = $form->getData();
                $em = $this->getDoctrine()->getManager();
                if ($data->getEmail()) {
                    $this->getUser()->setEmail($data->getEmail());
                    $this->getUser()->setEmailCanonical($data->getEmail());
                }
                if ($data->getPassword()) {
                    $this->getUser()->setPassword($data->getPassword());
                }
                if ($data->getName()) {
                    $this->getUser()->setName($data->getName());
                }
                if ($data->getAge()) {
                    $this->getUser()->setAge($data->getAge());
                }
                if ($data->getFamille()) {
                    $this->getUser()->setFamille($data->getFamille());
                }
                if ($data->getRace()) {
                    $this->getUser()->setRace($data->getRace());
                }
                if ($data->getNourriture()) {
                    $this->getUser()->setNourriture($data->getNourriture());
                }
                if ($data->getProfilePictureFile()) {
                    $this->getUser()->setProfilPic($this->get('app.profil_uploader')->upload($data->getProfilePictureFile(), $this->getUser()));
                }
                $em->persist($this->getUser());
                $em->flush();
                return $this->redirect($this->generateUrl("dash_board_homepage"));
            }
        }
        return $this->render("DashBoardBundle::update.html.twig", $param);
    }

    public function addFriendAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("UserBundle:User")->find($id);
        if (!$this->getUser()->getFriends()->contains($user))
        {
            $this->getUser()->getFriends()->add($user);
            $em->persist($this->getUser());
            $em->flush();
        }
        return $this->redirect($this->generateUrl('dash_board_homepage'));
    }

    public function deleteFriendAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("UserBundle:User")->find($id);
        if ($this->getUser()->getFriends()->contains($user))
        {
            $this->getUser()->getFriends()->removeElement($user);
            $em->persist($this->getUser());
            $em->flush();
        }
        return $this->redirect($this->generateUrl('dash_board_homepage'));
    }
}
