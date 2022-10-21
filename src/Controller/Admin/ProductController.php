<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Pictures;
use App\Form\ProductType;
use App\Form\PicturesType;
use App\Repository\ProductRepository;
use App\Repository\PicturesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image =$form->get('image')->getData();
            if(!is_null($image)){
                $new_name_image = uniqid() . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('upload_dir'),
                    $new_name_image
                );
                $product->setImage($new_name_image);

            }else{
                $product->setImage("defaultImage.png");
            }
            $productRepository->save($product,true);
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product, Request $request, PicturesRepository $picturesRepository ): Response
    {
        $picture = new Pictures();
        $form = $this->createForm(PicturesType::class, $picture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictures = $form->get('name')->getData();
            if($pictures){
                $new_name_picture = uniqid() . '.' . $pictures->guessExtension();
                $pictures->move(
                    $this->getParameter('upload_dir'),
                    $new_name_picture
                );
                $picture->setName($new_name_picture);
            }
            $picture->setProduct($product);

            $picturesRepository->save($picture, true);
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
            
        }
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $old_name_image = $product->getImage();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if(!is_null($image))
            {
                $new_name_image = uniqid() . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('upload_dir'),
                    $new_name_image
                );
                $product->setImage($new_name_image);
                $path = $this->getParameter('upload_dir') . $old_name_image;
                if($old_name_image != "defaultImage.png"){
                unlink($path);
                }
            }

            elseif (is_null($image) && is_null($old_name_image))
            {
                $product->setImage('defaultImage.png');
            }

            else
            {
                $product->setImage($old_name_image);
            }
            $productRepository->save($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
