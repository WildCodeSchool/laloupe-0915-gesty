<?php

namespace WCS\CantineBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\Sonata\UserBundle\Entity\User;

class DownloadController extends Controller
{
 
    /**
    * Renvoit un fichier "pièce justificative" donné
    * appartenant à l'utilisateur connecté
    * dans l'espace Parent du site.
    *
    * @param numeric type de pièce justificative
    * @return Response renvoit la réponse HTTP.
    */
    public function downloadParentAction($type_file)
    {
        // get the connected user
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        return $this->download($type_file, $user);
    }

 
    /**
    * Renvoit un fichier "pièce justificative" donné
    * appartenant à un utilisateur donné
    * depuis l'espace Comptable du site.
    *
    * @param numeric type de pièce justificative
    * @param numeric id user (parent)
    * @return Response renvoit la réponse HTTP.
    */
    public function downloadComptableAction($type_file, $id_user)
    {
        if (empty($id_user)) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->get('fos_user.user_manager')->findUserBy(array('id'=>$id_user));
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        return $this->download($type_file, $user);
    }

 
    /**
    * Renvoit le fichier pour une pièce justificative 
    * donnée et un utilisateur donné.
    *
    * @param numeric type de pièce justificative
    * @param User instance d'un "User" qui possède le fichier que l'on souhaite télécharger
    * @return Response renvoit la réponse HTTP.
    */
    private function download($type_file, &$user)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        // ensure the type file is in the accepted range
        if (!preg_match('/^[1-5]$/', $type_file)) {
            throw $this->createAccessDeniedException();   
        }

        $paths = array(
            User::type_Domicile     => $user->getAbsolutePathDomicile(),
            User::type_Prestations  => $user->getAbsolutePathPrestations(),
            User::type_Salaire1     => $user->getAbsolutePathSalaire1(),
            User::type_Salaire2     => $user->getAbsolutePathSalaire2(),
            User::type_Salaire3     => $user->getAbsolutePathSalaire3()
            );

        $ext = strtolower(pathinfo($paths[$type_file], PATHINFO_EXTENSION));

        $content_types = array(
            'pdf'   => 'application/pdf',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'png'   => 'image/png'
            );

        // check the extension
        if (!array_key_exists($ext, $content_types)) {
            throw $this->createAccessDeniedException();   
        }

        $new_filenames = array(
            User::type_Domicile     => "justif_domicile.".$ext,
            User::type_Prestations  => "justif_prestations.".$ext,
            User::type_Salaire1     => "justif_salaire_1.".$ext,
            User::type_Salaire2     => "justif_salaire_2.".$ext,
            User::type_Salaire3     => "justif_salaire_3.".$ext,
            );

        // build the HTTP response        
        $response = new Response();
        $response->headers->set('Cache-Control',        'no-cache');
        $response->headers->set('Content-Type',         $content_types[$ext]);
        $response->headers->set('Content-Length',       filesize($paths[$type_file]));
        $response->headers->set('Content-Disposition', 'inline; filename="'.$new_filenames[$type_file].'"');
        $response->setContent(file_get_contents($paths[$type_file]));
        return $response;
    }
}