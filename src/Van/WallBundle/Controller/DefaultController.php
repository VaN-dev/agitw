<?php

namespace Van\WallBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
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
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
            $dm = $this->get('doctrine_mongodb')->getManager();
            
            $post = new Post;
            $post->setFrom($this->getUser());
            $post->setTo($this->getUser());
            $form = $this->createForm(new PostType(), $post, array('dm' => $dm,));
            
            $post_repository = $dm->getRepository('Van\WallBundle\Document\Post');
            $qb = $post_repository->createQueryBuilder('Van\WallBundle\Document\Post')->field('to')->references($this->getUser())->sort('date', 'desc');
            $query = $qb->getQuery();
            $tmpPosts = $query->execute();
            $posts = array();
            if(!empty($tmpPosts)) {
                foreach($tmpPosts as $key => $post) {
                    $post->setContent(\Van\WallBundle\Utils\Utils::buildLink(\Van\WallBundle\Utils\Utils::sanitizePost($post->getContent())));
                    $posts[] = $post;
                }
            }
            
            $users = $this->get('doctrine_mongodb')
                ->getManager()
                ->createQueryBuilder('VanUserBundle:User')
                ->field('id')->notEqual($this->getUser()->getId())
                ->sort('username', 'ASC')
                ->getQuery()
                ->execute();
            $users = $this->getUser()->excludeFriends($users);
            
            $invitation_repository = $dm->getRepository('Van\WallBundle\Document\Invitation');
            $qb = $invitation_repository->createQueryBuilder('Van\WallBundle\Document\Invitation')->field('to')->references($this->getUser())->field('state')->equals(0)->sort('date', 'desc');
            $query = $qb->getQuery();
            $invitations = $query->execute();
            
            return $this->render('VanWallBundle:Default:index.html.twig', array(
                'form' => $form->createView(), 
                'posts' => $posts,
                'users' => $users,
                'invitations' => $invitations,
                'friends' => $this->getUser()->getFriends()
            ));
        }
        else {
            return $this->render('VanWallBundle:Default:index.html.twig');
        }
    }
    
    public function publishAction()
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        
        $user = $this->getUser();
        $post = new Post;
        
        $form = $this->createForm(new PostType(), $post, array('dm' => $dm,));

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            $post->setFrom($user);
            
            if ($form->isValid()) {
                
                $dm->persist($post);
                $dm->flush();

                $this->get('session')->getFlashBag()->add('info', 'Post bien ajouté');

                return $this->redirect($this->generateUrl('vanwall_index'));
            }
        }
    }
    
    public function inviteAction()
    {
        $user = $this->getUser();
        
        $dm = $this->get('doctrine_mongodb')->getManager();
        
        $invitation = new Invitation;
        
        $form = $this->createForm(new InvitationType(), $invitation, array('dm' => $dm,));
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $invitation->setFrom($user);
                $invitation->setState(0);
                
                $dm->persist($invitation);
                $dm->flush();

                $this->get('session')->getFlashBag()->add('info', 'Invitation bien envoyée');

                return $this->redirect($this->generateUrl('vanwall_index'));
            }
        }
    }
    
    public function invitationAction($id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $invitation = $dm->getRepository('Van\WallBundle\Document\Invitation')->find($id);
        
        $user = $this->getUser();
        
        $users = $this->get('doctrine_mongodb')
            ->getManager()
            ->createQueryBuilder('Van\UserBundle\Document\User')
            ->field('id')->notEqual($this->getUser()->getId())
            ->sort('username', 'ASC')
            ->getQuery()
            ->execute();
        $users = $this->getUser()->excludeFriends($users);
        
        $invitation_repository = $dm->getRepository('Van\WallBundle\Document\Invitation');
            $qb = $invitation_repository->createQueryBuilder('Van\WallBundle\Document\Invitation')->field('to')->references($this->getUser())->field('state')->equals(0)->sort('date', 'desc');
            $query = $qb->getQuery();
            $invitations = $query->execute();
        
        return $this->render('VanWallBundle:Default:invitation.html.twig', array( 
            'users' => $users,
            'invitations' => $invitations,
            'friends' => $this->getUser()->getFriends(),
            'user' => $user,
            'invitation' => $invitation,
        ));
    }
    
    public function acceptInvitationAction($id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $invitation = $dm->getRepository('Van\WallBundle\Document\Invitation')->find($id);
        
        $invitation->setState(1);
        
        $dm->persist($invitation);
        
        $user = $this->getUser();
        $user->addFriend($invitation->getFrom());
        $dm->persist($user);
        
        $invitation->getFrom()->addFriend($user);
        $dm->persist($invitation->getFrom());
        
        $dm->flush();
        
        return $this->redirect($this->generateUrl('vanwall_index'));
    }
    
    public function declineInvitationAction($id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $invitation = $dm->getRepository('Van\WallBundle\Document\Invitation')->find($id);
        
        $invitation->setState(2);
        
        $dm->persist($invitation);
        $dm->flush();
        
        return $this->redirect($this->generateUrl('vanwall_index'));
    }
}
