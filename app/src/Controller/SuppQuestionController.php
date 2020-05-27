<?php

namespace App\Controller;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;





class SuppQuestionController extends AbstractController
{
    /**
     * @Route("/suppquestion/{id}", name="suppquestion")
     */
    public function suppquestion($id, QuestionRepository $questionRepository,Request $request,PaginatorInterface $paginator,ReponseRepository $reponseRepository,TranslatorInterface $translator)
    {      
        $question = $questionRepository
        ->find($id);
        //check existing question
        if ($question) {
            //check existing reponse of question
            $reponse = $reponseRepository
            ->findBy(
                ['question' => $id]
            );
            if (!$reponse) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($question);
                $entityManager->flush();
               
                $entityManager = $this->getDoctrine()->getManager();
                $ques = $entityManager->getRepository(Question::class)->findAll();
                $ques = $entityManager->getRepository(Question::class)->findAllOrderDate();
                $message=$translator->trans('Question supprimer avec succes');
                $quest= $paginator->paginate(
                    $ques,
                    $request->query->getInt('page',1),
                    15
                );
                return $this->render('home/index.html.twig', [
                    'message' => $message,
                    'questions' => $quest
                ]);
            }else{
                $notice=$translator->trans('Vous pouvez pas supprimer la question');
                $this->addFlash(
                    'notice',
                    $notice
                );                
                return $this->redirect('/VoirQuestionReponses/'.$id);
            }
        }else{
            $message=$translator->trans('Question existe pas');
            return $this->render('home/index.html.twig', [
                'message' => $message,
            ]); 
        }
        
    }
}
