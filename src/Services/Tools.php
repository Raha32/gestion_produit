<?php 


namespace App\Services;


use App\Entity\User;

use Symfony\Component\Security\Core\Security;


class Tools
{

    private $security;
  

    public function __construct(Security $security)
    {
        $this->security = $security;
      
    }

    /**
     * Undocumented function
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->security->getUser();
    }
    
   /**
     * Undocumented function
     *
     * @return Boolean
     */
    public function testDonneeUser(){
        $user = $this->getUser();
        $nom_user = $user->getNom();
        $user_prenom = $user->getPrenom();
        $adresse  = $user->getAdresse();

        if (!$nom_user || !$user_prenom || !$adresse) {
            return true;
          }else{
            return false;
          }


    }
}