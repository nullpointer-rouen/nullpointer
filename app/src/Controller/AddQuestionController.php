<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Question;
use App\Entity\Tag;
use App\Entity\Users;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


class AddQuestionController extends AbstractController
{
    /**
     * @Route("/add/question", name="add_question")
     */
    public function index(Request $request,KernelInterface $kernel,TranslatorInterface $translator)
    {   
     
        
        $manager = $this->getDoctrine()->getManager();

        //getting the user by id with a repository

        if($request->request->count()>0){
            $tags=$request->request->get('tag');
            $user = $this->getUser();
    
            //creating a new question with the request values
    
            $question = new Question();
            if(($request->request->get('title')=="")||($request->request->get('body')=="")||((count($tags))>6)){
                $message=$translator->trans('la question doit avoir un titre et un corps et un maximum de 5 balises. ps: arrêtez de jouer avec l\'application');
                $this->addFlash(
                    'notice',
                    $message
                ); 
                //the question must have a title and a body and a maximum of 5 tags. ps:stop playing with the app               
                return $this->render('add_question/index.html.twig');
            }
            else{
            $body=$request->request->get('body');
            $title=$request->request->get('title');    
            $question->setTitleQuestion($title)
                     ->setBodyQuestion($body)
                     ->setDateQuestion(new \DateTime('now'))
                     ->setNoteQuestion(0)
                     ->setUser($user);
    
            //persisting data to database with the entity manager
    
            $addedtags=array();
            $cpt=0;
        foreach ($tags as $key=>$val){
            if(trim($val)!='' && $cpt<5){
                array_push($addedtags,[$val]);
            if($this->getDoctrine()->getRepository(Tag::class)->findOneBy(['labeltag'=>$val])){
                $tag = $this->getDoctrine()->getRepository(Tag::class)->findOneBy(['labeltag'=>$val]);
            }else{
                $tag=new Tag();
                $tag->setLabeltag($val);   
            }
            if(!in_array($addedtags,[$val])){
                $cpt++;
            $question->addQuestiontag($tag);
            }
        }
        }
        $manager->persist($question);
        $manager->flush();
        /////
        
        
        /////
       return $this->redirect('/VoirQuestionReponses/'.$question->getId());
  
    }
}
                
        return $this->render('add_question/index.html.twig', [
            'controller_name' => 'AddQuestionController',
        ]);

       
    }
}
