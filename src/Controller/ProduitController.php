<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ProduitsRepository;
use App\Entity\Produits;
use App\Form\ProduitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    #[IsGranted('ROLE_USER')]
    public function index(ProduitsRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $produit = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), 
            8
        );
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'produit' => $produit
        ]);
    }

    #[Route('/produitcreate', name: 'app_produitcreate', methods: ['POST', 'GET'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request,
    EntityManagerInterface $manager
    ): Response
    {
        $produit = new Produits();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $produit = $form->getData();
            $manager->persist($produit);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre nouvelle produit à été ajoutée avec succès !'
            );
           return $this->redirectToRoute('app_produit');
        }

        return $this->render('Produit/create.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'ProduitController',
        ]);
    }

    #[Route('/produitedit/{id}', name: 'app_produitedit', methods: ['POST', 'GET'])]
    public function edit( Produits $produit, Request $request, EntityManagerInterface $manager) : Response {
      
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $produit = $form->getData();
            $manager->persist($produit);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre produit à été modifié avec succès !'
            );
           return $this->redirectToRoute('app_produit');
        }

        return $this->render('produit/edit.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produitdelete/{id}', name: 'app_produitdelete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager,Produits $produit) : Response{
        if (!$produit) {
            $this->addFlash(
                'warning',
                'Le produit n\'a pas été trouvée !'
            );
            return $this->redirectToRoute('app_produit');
          }
        $manager->remove($produit);
        $manager->flush();
        $this->addFlash(
            'success',
            'Votre  produit à été supprimé avec succès !'
        );
        return $this->redirectToRoute('app_produit');
    }

    #[Route('/produitdetails/{id}', name: 'app_produitdetails')]
    #[IsGranted('ROLE_USER')]
    public function details( Request $request, ProduitsRepository $repository, int $id): Response
    {
        $produit = $repository->find($id);
        return $this->render('produit/details.html.twig', [
            'controller_name' => 'ProduitController',
            'produit' => $produit,
        ]);
    }
}
