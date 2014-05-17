<?php

namespace Van\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Van\WallBundle\Document\Post;
use Van\WallBundle\Form\PostType;

use Van\WallBundle\Document\Invitation;
use Van\WallBundle\Form\InvitationType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VanUserBundle:Default:index.html.twig');
    }
    
    public function profileAction($id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        
        $post = new Post;
        $form = $this->createForm(new PostType(), $post);
        

        
        $user = $dm->getRepository('Van\UserBundle\Document\User')->find($id);

        $invitation = new Invitation;
        $invitation->setTo($user);
        $form_invitation = $this->createForm(new InvitationType(), $invitation, array('dm' => $dm,));
        
        $users = $this->get('doctrine_mongodb')
            ->getManager()
            ->createQueryBuilder('VanUserBundle:User')
            ->field('id')->notEqual($this->getUser()->getId())
            ->sort('username', 'ASC')
            ->getQuery()
            ->execute();
        
        return $this->render('VanUserBundle:Default:profile.html.twig', array(
            'user' => $user,
            'form' => $form->createView(), 
            'form_invitation' => $form_invitation->createView(), 
            'users' => $users
        ));
    }
}
