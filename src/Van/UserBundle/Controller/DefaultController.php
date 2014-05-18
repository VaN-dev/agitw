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
        
        $user = $dm->getRepository('Van\UserBundle\Document\User')->find($id);
        
        $users = $this->get('doctrine_mongodb')
            ->getManager()
            ->createQueryBuilder('VanUserBundle:User')
            ->field('id')->notEqual($this->getUser()->getId())
            ->sort('username', 'ASC')
            ->getQuery()
            ->execute();
        $users = $this->getUser()->excludeFriends($users);
        
        $invitation = new Invitation;
        $invitation->setTo($user);
        $form_invitation = $this->createForm(new InvitationType(), $invitation, array('dm' => $dm,));
        
        if($this->getUser()->isFriendWith($user)) {
        
            $post = new Post;
            $post->setTo($user);
            $form = $this->createForm(new PostType(), $post, array('dm' => $dm,));
            
            $post_repository = $dm->getRepository('Van\WallBundle\Document\Post');
            $qb = $post_repository->createQueryBuilder('Van\WallBundle\Document\Post')->field('to')->references($user)->sort('date', 'desc');
            $query = $qb->getQuery();
            $tmpPosts = $query->execute();
            $posts = array();
            if(!empty($tmpPosts)) {
                foreach($tmpPosts as $key => $post) {
                    $post->setContent(\Van\WallBundle\Utils\Utils::sanitizePost($post->getContent()));
                    $posts[] = $post;
                }
            }
        
            return $this->render('VanUserBundle:Default:profile.html.twig', array(
                'user' => $user,
                'form' => $form->createView(), 
                'posts' => $posts,
                'form_invitation' => $form_invitation->createView(), 
                'users' => $users,
                'friends' => $this->getUser()->getFriends()
            ));
        }
        else {
            return $this->render('VanUserBundle:Default:profile.html.twig', array(
                'user' => $user,
                'form_invitation' => $form_invitation->createView(), 
                'users' => $users,
                'friends' => $this->getUser()->getFriends()
            ));
        }
    }
}
