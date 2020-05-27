<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Users;
use App\Entity\Reponse;
use App\Entity\Question;
use App\Entity\Notequestion;
use App\Repository\QuestionRepository;
use App\Entity\Notereponse;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


class NotationController extends AbstractController
{
    /**
     * @Route("/notation", name="notation")
     */
    public function index(Request $request, TranslatorInterface $translator)
    {
      /*  $tokenInterface = $this->get('security.token_storage')->getToken();
        $isAuthenticated = $tokenInterface->isAuthenticated();*/
        if(!is_null($this->getUser())){
        $manager = $this->getDoctrine()->getManager();
        $minupvote=15;
        $mindownvote=125;
        $upvote=10;
        $downvote=-2;
        $downvoter=-1;
        if($request->request->count()>0){
            $id=$request->request->get('id');
            $user =$this->getUser();            
            if($request->request->get('element')=="answer"){
                $reponse = $this->getDoctrine()->getRepository(Reponse::class)->findOneBy(['id'=>$id]);
                $iduserrequest=$reponse->getUser();
                if($user==$reponse->getUser()){
                    $jsonData = array();
                    $msg= $translator->trans('vous ne pouvez pas noter votre reponse');
                    $temp = array(
                        'msg' => $msg,  
                     );  
                     array_push($jsonData,$temp);
                     return new JsonResponse($jsonData);
                }else{
                    if($request->request->get('note')=='upvote'){
                    if($user->getNote()<$minupvote){
                        $jsonData = array();
                        $msg= $translator->trans('vous devez avoir 15 pour upvote');
                        $temp = array(
                            'msg' => $msg,  
                        );   
                     array_push($jsonData,$temp);
                     return new JsonResponse($jsonData);
                    }else{
                    if($this->getDoctrine()->getRepository(Notereponse::class)->findOneBy(array('iduser'=>$user->getId(),'idreponse'=>$reponse->getId()))){
                       $manager->remove($this->getDoctrine()->getRepository(Notereponse::class)->findOneBy(array('iduser'=>$user->getId(),'idreponse'=>$reponse->getId())));
                        $manager->flush();
                        $reponse->setNotereponse($reponse->getNotereponse()-1);
                        $manager->persist($reponse);
                        $manager->flush();
                        $userquestion=$reponse->getUser();
                        $userquestion->setNote($userquestion->getNote()-$upvote);
                        $manager->persist($userquestion);
                        $manager->flush();
                        $jsonData = array();
                        $temp = array(
                            'note' => $reponse->getNotereponse(),   
                         );  
                         array_push($jsonData,$temp);
                         return new JsonResponse($jsonData);
                    }else{
                        $notereponse=new Notereponse();
                        $notereponse->setIduser($user) ;
                        $notereponse->setIdreponse($reponse);
                        $notereponse->setNote(1);
                        $manager->persist($notereponse);
                        $manager->flush();
                        $reponse->setNotereponse($reponse->getNotereponse()+1);
                        $manager->persist($reponse);
                        $manager->flush();
                        $userquestion=$reponse->getUser();
                        $userquestion->setNote($userquestion->getNote()+$upvote);
                        $manager->persist($userquestion);
                        $manager->flush();
                        $jsonData = array();
                        $temp = array(
                            'note' => $reponse->getNotereponse(),  
                         );  
                         array_push($jsonData,$temp);
                         return new JsonResponse($jsonData);
                    }
                }
                }
                if($request->request->get('note')=='downvote'){
                    if($user->getNote()<$mindownvote){
                        $jsonData = array();
                        $msg= $translator->trans('vous devez avoir 125 pour downvote');
                        $temp = array(
                            'msg' => $msg,  
                        );
                     array_push($jsonData,$temp);
                     return new JsonResponse($jsonData);
                    }else{
                    if($this->getDoctrine()->getRepository(Notereponse::class)->findOneBy(array('iduser'=>$user->getId(),'idreponse'=>$reponse->getId()))){
                       $manager->remove($this->getDoctrine()->getRepository(Notereponse::class)->findOneBy(array('iduser'=>$user->getId(),'idreponse'=>$reponse->getId())));
                        $manager->flush();
                        $reponse->setNotereponse($reponse->getNotereponse()+1);
                        $manager->persist($reponse);
                        $manager->flush();
                        $userquestion=$reponse->getUser();
                        $userquestion->setNote($userquestion->getNote()-$downvote);
                        $manager->persist($userquestion);
                        $manager->flush();
                        $user->setNote($user->getNote()-$downvoter);
                        $manager->persist($user);
                        $manager->flush();
                        $jsonData = array();
                        $temp = array(
                            'note' => $reponse->getNotereponse(),   
                         );  
                         array_push($jsonData,$temp);
                         return new JsonResponse($jsonData);
                    }else{
                        $notereponse=new Notereponse();
                        $notereponse->setIduser($user) ;
                        $notereponse->setIdreponse($reponse);
                        $notereponse->setNote(-1);
                        $manager->persist($notereponse);
                        $manager->flush();
                        $reponse->setNotereponse($reponse->getNotereponse()-1);
                        $manager->persist($reponse);
                        $manager->flush();
                        $userquestion=$reponse->getUser();
                        $userquestion->setNote($userquestion->getNote()+$downvote);
                        $manager->persist($userquestion);
                        $manager->flush();
                        $user->setNote($user->getNote()+$downvoter);
                        $manager->persist($user);
                        $manager->flush();
                        $jsonData = array();
                        $temp = array(
                            'note' => $reponse->getNotereponse(),  
                         );  
                         array_push($jsonData,$temp);
                         return new JsonResponse($jsonData);
                    }
                }
                }

                }
            }
            if($request->request->get('element')=="question"){
                $question = $this->getDoctrine()->getRepository(Question::class)->findOneBy(['id'=>$id]);
                $iduserrequest=$question->getUser();
                if($user==$question->getUser()){
                    $jsonData = array();
                    $msg= $translator->trans('vous ne pouvez pas noter votre question');
                    $temp = array(
                        'msg' => $msg,  
                    );
                    
                     array_push($jsonData,$temp);
                     return new JsonResponse($jsonData);
                }else{
                    if($request->request->get('note')=='upvote'){
                    if($user->getNote()<$minupvote){
                        $jsonData = array();
                        $msg= $translator->trans('vous devez avoir 15 pour upvote');
                        $temp = array(
                            'msg' => $msg,  
                        );
                    
                     array_push($jsonData,$temp);
                     return new JsonResponse($jsonData);
                    }else{
                    if($this->getDoctrine()->getRepository(Notequestion::class)->findOneBy(array('iduser'=>$user->getId(),'idquestion'=>$question->getId()))){
                       $manager->remove($this->getDoctrine()->getRepository(Notequestion::class)->findOneBy(array('iduser'=>$user->getId(),'idquestion'=>$question->getId())));
                        $manager->flush();
                        $question->setNotequestion($question->getNotequestion()-1);
                        $manager->persist($question);
                        $manager->flush();
                        $userquestion=$question->getUser();
                        $userquestion->setNote($userquestion->getNote()-$upvote);
                        $manager->persist($userquestion);
                        $manager->flush();
                        $jsonData = array();
                        $temp = array(
                            'note' => $question->getNotequestion(),   
                         );  
                         array_push($jsonData,$temp);
                         return new JsonResponse($jsonData);
                    }else{
                        $notequestion=new Notequestion();
                        $notequestion->setIduser($user) ;
                        $notequestion->setIdquestion($question);
                        $notequestion->setNote(1);
                        $manager->persist($notequestion);
                        $manager->flush();
                        $question->setNotequestion($question->getNotequestion()+1);
                        $manager->persist($question);
                        $manager->flush();
                        $userquestion=$question->getUser();
                        $userquestion->setNote($userquestion->getNote()+$upvote);
                        $manager->persist($userquestion);
                        $manager->flush();
                        $jsonData = array();
                        $temp = array(
                            'note' => $question->getNotequestion(),  
                         );  
                         array_push($jsonData,$temp);
                         return new JsonResponse($jsonData);
                    }
                }
                }
                if($request->request->get('note')=='downvote'){
                    if($user->getNote()<$mindownvote){
                        $jsonData = array();
                        $msg= $translator->trans('vous devez avoir 125 pour downvote');
                        $temp = array(
                            'msg' => $msg,  
                        );
                     
                     array_push($jsonData,$temp);
                     return new JsonResponse($jsonData);
                    }else{
                    if($this->getDoctrine()->getRepository(Notequestion::class)->findOneBy(array('iduser'=>$user->getId(),'idquestion'=>$question->getId()))){
                       $manager->remove($this->getDoctrine()->getRepository(Notequestion::class)->findOneBy(array('iduser'=>$user->getId(),'idquestion'=>$question->getId())));
                        $manager->flush();
                        $question->setNotequestion($question->getNotequestion()+1);
                        $manager->persist($question);
                        $manager->flush();
                        $userquestion=$question->getUser();
                        $userquestion->setNote($userquestion->getNote()-$downvote);
                        $manager->persist($userquestion);
                        $manager->flush();
                        $jsonData = array();
                        $temp = array(
                            'note' => $question->getNotequestion(),   
                         );  
                         array_push($jsonData,$temp);
                         return new JsonResponse($jsonData);
                    }else{
                        $notequestion=new Notequestion();
                        $notequestion->setIduser($user) ;
                        $notequestion->setIdquestion($question);
                        $notequestion->setNote(-1);
                        $manager->persist($notequestion);
                        $manager->flush();
                        $question->setNotequestion($question->getNotequestion()-1);
                        $manager->persist($question);
                        $manager->flush();
                        $userquestion=$question->getUser();
                        $userquestion->setNote($userquestion->getNote()+$downvote);
                        $manager->persist($userquestion);
                        $manager->flush();
                        $jsonData = array();
                        $temp = array(
                            'note' => $question->getNotequestion(),  
                         );  
                         array_push($jsonData,$temp);
                         return new JsonResponse($jsonData);
                    }
                }
                }

                }
            }
        }
    }else{
        $jsonData = array();
        
        $msg1= $translator->trans('vous devez vous connecter pour voter');
        $temp = array(
            'error' => $msg1,  
         );  
         array_push($jsonData,$temp);
         return new JsonResponse($jsonData);
    }
}
}