<?php

namespace App\Controller;

use App\Form\SearchFormType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


use Elastica\Util;
//use Monolog\Handler\Curl\Util;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use FOS\ElasticaBundle\Finder\TransformedFinder;




class SearchController extends AbstractController
{
    /**
     * @Route({"en": "/search", "fr": "/recherche"}, name="search")
     */
    
    public function search(Request $request, SessionInterface $session, TransformedFinder $questionsFinder,PaginatorInterface $paginator){
        
        $formSearch=$this->createForm(SearchFormType::class);
        $formSearch->handleRequest($request);
        
        if ($request->query->get($formSearch->getName()) != null) {
            /** @var Users $user */
        //$token = $formSearch->getData();
        $formData = $request->query->get($formSearch->getName());
        $q=$formData['Search'];
        
        $paginations = !empty($q) ? $questionsFinder->findHybrid(Util::escapeTerm($q)) : [];
        $resul= array();
        foreach ($paginations as $value) {
            # code...
            $resul[]=$value->getTransformed();

        }
        
        $questions= $paginator->paginate(
            $resul,
            $request->query->getInt('page',1),
            15
        );
       
        $session->set('q', $q);
        
        $formSearch=$formSearch->createView();
        return $this->render('search/resultSearch.html.twig', compact('questions', 'q','formSearch'));

        }       
        $formSearch=$formSearch->createView();
        return $this->render('search/resultSearch.html.twig', compact('formSearch'));

    }

    private function findHybridPaginated(TransformedFinder $questionsFinder, string $query): Pagerfanta
    {
        $paginatorAdapter = $questionsFinder->createHybridPaginatorAdapter($query);

        return new Pagerfanta(new FantaPaginatorAdapter($paginatorAdapter));
    }


    /**
     * @Route("/viewSearch", name="viewSearch")
     */
    public function viewSearch(){
        $formSearch=$this->createForm(SearchFormType::class);
        
        $formSearch=$formSearch->createView();
        return $this->render('search/index.html.twig', compact('formSearch'));

    }

     
}
