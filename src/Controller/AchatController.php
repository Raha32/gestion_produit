<?php

namespace App\Controller;

use App\Services\Tools;
use App\Services\Panier;
use App\Services\CommandeManager;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DetailCommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AchatController extends AbstractController
{
    #[Route('/achat', name: 'app_achat')]
    public function index(CommandeRepository $commandeRepository, CommandeManager $commandeManager, Panier $panier, EntityManagerInterface $manager , Tools $tools): Response
    {
        if(!$this->getUser()){
           return $this->redirectToRoute('app_login');
        }
        if($tools->testDonneeUser()){
            return $this->redirectToRoute('app_form_user');
        }
        $commande = $commandeManager->getCommande();
        $tableau_detail_panier = $panier->getDetailPanier();
        $manager->persist($commande);
        foreach ($tableau_detail_panier as $ligne_panier) {
           $detailcommande = $commandeManager->getDetailCommande($commande, $ligne_panier);
           $manager->persist($detailcommande);
        }

        $manager->flush();
        $panier->deletePanier();
        $commandeRepository->save($commande, true);
        return $this->redirectToRoute('app_home');
    }
}
