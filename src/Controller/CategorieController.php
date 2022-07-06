<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoriesRepository;
use App\Entity\Categories;
use App\Form\CategorieType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    #[IsGranted('ROLE_USER')]
    public function index(CategoriesRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $categorie = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), 
            10
        );
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categorie' => $categorie
           
        ]);
    }
    #[Route('/categoriecreate', name: 'app_categoriecreate', methods: ['POST', 'GET'])]
    public function create(Request $request,
    EntityManagerInterface $manager
    ): Response
    {
        $categorie = new Categories();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $categorie = $form->getData();
            $manager->persist($categorie);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre nouvelle catégorie à été ajoutée avec succès !'
            );
           return $this->redirectToRoute('app_categorie');
        }

        return $this->render('categorie/create.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'CategorieController',
        ]);
    }
    #[Route('/categorieEdit/{id}', name: 'app_categorieEdit', methods: ['POST', 'GET'])]
    public function edit( Categories $categorie, Request $request, EntityManagerInterface $manager) : Response {
      
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $categorie = $form->getData();
            $manager->persist($categorie);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre  catégorie à été modifiée avec succès !'
            );
           return $this->redirectToRoute('app_categorie');
        }

        return $this->render('categorie/edit.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/categoriedelete/{id}', name: 'app_categoriedelete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager,Categories $categorie) : Response{
        if (!$categorie) {
            $this->addFlash(
                'warning',
                'La  catégorie n\'a pas été trouvée !'
            );
            return $this->redirectToRoute('app_categorie');
          }
        $manager->remove($categorie);
        $manager->flush();
        $this->addFlash(
            'success',
            'Votre  catégorie à été supprimée avec succès !'
        );
        return $this->redirectToRoute('app_categorie');
    }

    #[Route('/categoriedetails/{id}', name: 'app_categoriedetails')]
    public function details( Request $request, CategoriesRepository $repository, int $id): Response
    {
        $categorie = $repository->find($id);
        return $this->render('categorie/details.html.twig', [
            'controller_name' => 'CategorieController',
            'categorie' => $categorie,
        ]);
    }
}
