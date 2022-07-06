<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ProduitsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\SearchProduitsType;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    #[IsGranted('ROLE_USER')]
    public function index(ProduitsRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
       
        
        $produit = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), 
            8
        );
        return $this->render('main/index.html.twig', [
            'produit' => $produit,
        ]);
    }
}
