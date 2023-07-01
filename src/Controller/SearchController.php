<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\HairSalonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(
        HairSalonRepository $hairSalonRepository,
        Request $request
    ): Response 
    {
        $searchData = new SearchData();

        $form = $this->createForm(SearchType::class, $searchData);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            /* recupere l'element  */
            $searchData->page = $request->query->getInt('page', 1);
            $hairSalon = $hairSalonRepository->findByCodePostal($searchData);

            return $this->render('search/index.html.twig', [
                'form' => $form->createView(),
                'hairSalon' => $hairSalon
            ]);
        }

        return $this->render('search/index.html.twig', [
            'form' => $form->createView(),
            'hairSalon' => $hairSalonRepository->findByCodePostal($request->query->getInt('page', 1))
        ]);
    }

}