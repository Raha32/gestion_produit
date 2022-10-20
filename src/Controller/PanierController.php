<?php

namespace App\Controller;

use App\Services\Panier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(Panier $panier): Response
    {
        return $this->render('panier/index.html.twig', [
            'panier' =>$panier->getDetailPanier(),
            'totalTTC' =>$panier->getTotalPanier()
        ]);
    }

    #[Route('/ajoute-panier/{id}', name: 'app_add_produit_panier')]
    public function addProduit($id, Panier $panier): Response
    {
        $panier->addProduitPanier($id);
        return $this->redirectToRoute('app_panier');
    }

    #[Route('/effacer-panier', name: 'app_effacer_panier')]
    public function deletePanier(Panier $panier): Response
    {
        $panier->deletePanier();
        return $this->redirectToRoute('app_panier');
    }

    #[Route('/effacer-produit/{id}', name: 'app_effacer_produit')]
    public function deleteProduit($id,Panier $panier): Response
    {
        $panier->deleteProduitPanier($id);
        return $this->redirectToRoute('app_panier');
    }
    
    #[Route('/effacer-quantite-produit/{id}', name: 'app_effacer_quantite_produit')]
    public function deleteQuantity($id,Panier $panier): Response
    {
        $panier->deleteQuantityProduitPanier($id);
        return $this->redirectToRoute('app_panier');
    }
}
