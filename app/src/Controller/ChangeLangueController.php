<?php

namespace App\Controller;


  // --- Imports ---

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
class ChangeLangueController extends AbstractController {

    // --- Méthodes ---

  /**
   * @Route("/change-langue/{locale}", name="change_langue")
   */
  public function changeLangue($locale, Request $request) {
    //on stocke la langue demander dans la session
    $request->getSession()->set('_locale',$locale);
    $request->setLocale($locale);
    //on revient sur la page precedente
    return $this->redirect($request->headers->get('referer'));
  }

}

?>
 