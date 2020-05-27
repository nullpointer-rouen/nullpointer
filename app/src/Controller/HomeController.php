<?php

namespace App\Controller;
use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\Tag;
use App\Form\AddResponseType;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/home/{tri}", name="app_home")
     */
    public function index(int $tri = 1,Request $request,PaginatorInterface $paginator)
    {
        //tri par date
        if($tri == 1){
        $entityManager = $this->getDoctrine()->getManager();
        $resultats = $entityManager->getRepository(Question::class)->findAllOrderDate();
       
        $questions= $paginator->paginate(
            $resultats,
            $request->query->getInt('page',1),
            15
        );
        $totalResponses = 0;            
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'questions' => $questions
           
        ]);
        //tri par vue
        }elseif($tri == 2){
            $entityManager = $this->getDoctrine()->getManager();
        $resultats = $entityManager->getRepository(Question::class)->findAllOrderVu();
        $questions= $paginator->paginate(
            $resultats,
            $request->query->getInt('page',1),
            15
        );
        $totalResponses = 0;            
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'questions' => $questions
           
        ]);
        }
    }
}
