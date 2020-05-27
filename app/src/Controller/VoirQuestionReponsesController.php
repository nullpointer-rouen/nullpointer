<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Notereponse;
use App\Entity\Notequestion;
use App\Entity\Reponse;
use App\Entity\Tag;
use App\Form\AddResponseType;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use App\Repository\NotereponseRepository;
use App\Repository\NoterquestionRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use FOS\ElasticaBundle\HybridResult;
use Knp\Component\Pager\PaginatorInterface;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Contracts\Translation\TranslatorInterface;
use Elastica\Util;

class VoirQuestionReponsesController extends AbstractController
{

    /**
     * @Route("/validreponse/{id}" , name="valider_reponse")
     */
    public function validreponse($id,TranslatorInterface $translator)
    {   
        $entityManager = $this->getDoctrine()->getManager();
        $reponse = $entityManager->getRepository(Reponse::class)->find($id);

        if (!$reponse) {
            $message=$translator->trans('Reponse non trouvee pour l\'id ');
            throw $this->createNotFoundException(
                $message.$id
            );
        }
        $questionManager = $this->getDoctrine()->getManager();
        $question = $entityManager->getRepository(Question::class)->find($reponse->getQuestion()->getId());
        
        if($reponse->getValide()==false){
        $reponse->setValide(true);
        $entityManager->flush();
        $question->setResolve(true);
        $entityManager->flush();
        $useranwer=$reponse->getUser();
        $useranwer->setNote($useranwer->getNote()+15);
        $entityManager->persist($useranwer);
        $entityManager->flush();
        $uservalidator=$this->getUser();
        $uservalidator->setNote($uservalidator->getNote()+2);
        $entityManager->persist($uservalidator);
        $entityManager->flush();
        }else{
        $reponse->setValide(false);
        $entityManager->flush();
        $question->setResolve(false);
        $entityManager->flush();
        $useranwer=$reponse->getUser();
        $useranwer->setNote($useranwer->getNote()-15);
        $entityManager->persist($useranwer);
        $entityManager->flush();
        $uservalidator=$this->getUser();
        $uservalidator->setNote($uservalidator->getNote()-2);
        $entityManager->persist($uservalidator);
        $entityManager->flush();
        }

    
        return $this->redirect('/VoirQuestionReponses/'.$reponse->getQuestion()->getId());
    }
    
    /**
     * @Route("/VoirQuestionReponses/{id}", name="voir_question_reponses")
     */
    public function voirQuestionReponses($id,QuestionRepository $repo, Request $request, UsersRepository $userRepo, ReponseRepository $reponseRepo, SessionInterface $session, TransformedFinder $questionsFinder,PaginatorInterface $paginator)
    {   
        if ($repo->find($id)){
        
         //incremente nombre de vu
        $addvu= $repo->find($id);
        if($this->getUser()!= $addvu->getUser() ){
        
        $addvu->setVu($addvu->getVu()+1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($addvu);
        $entityManager->flush();
        }
        //get the ID of the question
        $question = $repo->find($id);
        
        //get the ID of the User
        $user = $this->getUser();
        //create an instance of Reponse Entity
        $response = new Reponse();
        
        $form = $this->createForm(AddResponseType::class, $response);

        //analyse the http request
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

             //fill in the fields in the response table
            $response->setDatereponse(new \DateTime())
                    ->setNotereponse(0)
                    ->setUser($user)
                    ->setQuestion($question);
            
            //create a nmanager to send data in the database
            $entityManager = $this->getDoctrine()->getManager();  
            $entityManager->persist($response);
            $entityManager->flush();
            //get all response
            $reponses = $question->getReponses();

        }
        $reponses = $question->getReponses();
        $tags=$question->getQuestiontag();
        $notereponses=Array();
        $notequestions=Array();
        if(!empty($reponses)&&!empty($this->getUser())){
       foreach($reponses as $reponse){   
           if($notereponse=$this->getDoctrine()->getManager()->getRepository(Notereponse::class)->find(['idreponse'=>$reponse->getId(),'iduser'=>$this->getUser()->getId()])){
            $notereponses+=array($notereponse->getIdreponse()->getId()=>$notereponse->getNote());
    }
       }
    
       if($notequestion=$this->getDoctrine()->getManager()->getRepository(Notequestion::class)->find(['idquestion'=>$question->getId(),'iduser'=>$this->getUser()->getId()])){
        $notequestions=array($notequestion->getIdquestion()->getId()=>$notequestion->getNote());
        }
    }

        //get see also
        //by title
        $token = ['seeAlso' => $question->getTitlequestion()];
        $q=$token['seeAlso'];
        $seeAlso = !empty($q) ? $questionsFinder->findHybridAlso(Util::escapeTerm($q)) : [];

        
        //render page question et ses reponse
        $reponses= $paginator->paginate(
            $reponses,
            $request->query->getInt('page',1),
            5
        );
        return $this->render('questions/questions.html.twig', [
            'controller_name' => 'VoirQuestionReponsesController',
             'question'=> $question,
             'seeAlso'=>$seeAlso,
             'reponses'=> $reponses,
             'tags'=>$tags,
             'notereponses'=> $notereponses,
             'notequestions'=>$notequestions,
             'formResponse' => $form->createView()

        ]);
    }else{
        $msgErreur = $translator->trans("la page demandée n'existe pas");
        return $this->render('questions/questions.html.twig', [
            'msgErreur' => $msgErreur

        ]);
    }

    }

    
}
