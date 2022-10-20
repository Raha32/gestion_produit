<?php

namespace App\Services;

use App\Services\Tools;
use App\Services\Panier;
use App\Entity\Commande;
use App\Entity\DetailCommande;
use DateTime;

class CommandeManager
{
    private $tools;

    public function __construct(Tools $tools, Panier $panier)
    {
        $this->tools = $tools;
        $this->panier = $panier;
    }

    public function getCommande(){
        $user = $this->tools->getUser();
        $commande = new Commande();

        $commande->setUser($user);
        $commande->setNom($user->getNom() . ' ' . $user->getPrenom());
        $commande->setAdresse($user->getAdresseComplete());
        $date_com = new DateTime();
        $commande -> setDateCom($date_com);
        $commande -> setTotal($this->panier->getTotalPanier());
        $commande -> setStatus(false);
        return $commande;
    }

    public function getDetailCommande(Commande $commande,$ligne_panier){
        $detail_commande = new DetailCommande();
        $detail_commande->setCommande($commande);
        $detail_commande->setName($ligne_panier['product']->getName());
        $detail_commande->setName($ligne_panier['product']->getName());
        $detail_commande->setPrixUnit($ligne_panier['product']->getPrix());
        $detail_commande->setQuantity($ligne_panier['quantity']);
        $detail_commande->setTotal($ligne_panier['total']);
        return $detail_commande;
    }
}