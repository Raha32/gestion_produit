<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Panier
{
    private $session;
    private $productRepository;

    public function __construct(SessionInterface $sessionInterface, ProductRepository $productRepository)
    {
        $this->session = $sessionInterface;
        $this->productRepository = $productRepository;
    }
/**
 * Recup le tableau de la session
 *
 * @return array
 */
    public function getPanier(){
        return $this->session->get('panier', []);
    }
/**
 * Ajoute un produit au panier si le produit n4existe pas
 * si non elle rajoute 1 a la quantite
 *
 * @param integer $id
 * @return void
 */
    public function addProduitPanier($id){
        $panier = $this->getPanier();
        if(!empty($panier[$id])){
            // si le produit ce trouve dans le panier je rajoute 1 a la quantite
            $panier[$id] = $panier[$id] + 1;
        } else {
            // sinon je rajoute le produit avec 1  comme quantite
            $panier[$id] = 1;
        }
        // j'update le panier dans la session
        $this->session->set('panier', $panier);
    }
    /**
     * efface le panier en entier
     *
     * @return void
     */
    public function deletePanier()
    {
        $this->session->remove('panier');
    }
    /**
     * Supprime un produit du panier
     *
     * @param integer $id
     * @return void
     */
    public function deleteProduitPanier($id)
    {
        $panier = $this->getPanier();
        if(!empty($panier[$id]))
        {
            unset($panier[$id]);
        }
        $this->session->set('panier', $panier);
    }
    /**
     * Undocumented function
     *
     * @param integer $id
     * @return void
     */
    public function deleteQuantityProduitPanier($id)
    {
        $panier = $this->getPanier();
        if(!empty($panier[$id])){
            if($panier[$id] > 1)
            {
                $panier[$id] = $panier[$id] - 1;
            }else{
                unset($panier[$id]);
            }
        }
        $this->session->set('panier', $panier);
    }

    public function getDetailPanier(){
        $panier = $this->getPanier();
        $panier_detail = [];
        
        foreach ($panier as $id => $quantity) {
            $product = $this->productRepository->find($id);
            $panier_detail [] = [
                'product' => $product,
                'quantity' => $quantity,
                'total' => $quantity * $product->getPrix()
            ];
        }
        return $panier_detail;
    }
}