<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Film;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use DateTimeInterface;

class FilmController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function listeFilms()
    {
        $films = $this->getDoctrine()->getRepository(Film::class)->findAll();

        $nbFilms = count($films);

        return $this->render('film/list.html.twig', [
            'films' => $films,
            'nbFilms' => $nbFilms
        ]);
    }


    /**
     * @Route("/addFilm", name="addFilm")
     */
    public function createFilm(ValidatorInterface $validator){
        $em = $this->getDoctrine()->getManager();

        $film = new Film();

        //$film->setDescription('C\'est un film plutot interessant !');
        $film->setTitre('Le titre de l\'annee');
        $film->setPrix(23.5);

        $em->persist($film);

        $em->flush();

        $error = $validator->validate($film);
        if(count($error) > 0){
            return new Response((string) $error, 400);
        }

        return new Response('Film ajouté !' . $film->getTitre());
    }

    /**
     * @Route("/showFilm/{id}", name="showFilm")
     */
    public function showFilm($id){
        $film = $this->getDoctrine()
            ->getRepository(Film::class)
            ->find($id);

        if(!$film){
            throw $this->createNotFoundException('Pas de film pour l\'id ' . $id);
        }

        return new Response('Check out this great film: ' . $film->getTitre());
    }

    /**
     * @Route("/genreFilms", name="genreFilms")
     */
    public function genreFilm(){
        //$films = $this->getDoctrine()->getRepository(Film::class)->getCategoryFilm();

        $films = $this->getDoctrine()->getRepository(Film::class)->findAll();
        
        $nbFilmsHorreur = 0;
        $nbFilmsAction = 0;
        $nbFilmsAnimation = 0;
        $nbFilmsThriller = 0;

        foreach($films as $film){
            $category = $film->getCategory()->getId();

            switch($category){
                case 1:
                    $nbFilmsHorreur++;
                    break;
                case 2:
                    $nbFilmsAction++;
                    break;
                case 3:
                    $nbFilmsAnimation++;
                    break;
                case 4:
                    $nbFilmsThriller++;
                    break;  
            }
        }

        return $this->render('film/genreFilm.html.twig', [
            'films' => $films,
            'nbFilmsHorreur' => $nbFilmsHorreur,
            'nbFilmsAction' => $nbFilmsAction,
            'nbFilmsAnimation' => $nbFilmsAnimation,
            'nbFilmsThriller' => $nbFilmsThriller,
        ]);
    }

    /**
     * @Route("/film/{id}", name="film")
     */
    public function descriptionFilm($id){

        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);

        $category = $film->getCategory()->getLibelleCat();
        
        return $this->render('film/film.html.twig', [
            'film' => $film,
            'category' =>$category
        ]);
    }

    /**
     * @Route("/newFilms", name="newFilms")
     */
    public function newFilms(){

        // Fct qui permet de recupere tout les films par ordre de date de sortie
        $filmsNouveau = $this->getDoctrine()->getRepository(Film::class)->getFilmsOrdreDate();
        $nbFilmsNouveau = count($filmsNouveau);

        return $this->render('film/nouveaute.html.twig', [
            'filmsNouveau' => $filmsNouveau,
            'nbFilmsNouveau' => $nbFilmsNouveau
        ]);
    }

    /**
     * @Route("/prochainFilms", name="prochainFilms")
     */
    public function prochainFilms(){

        // Fct qui permet de recupere tout les films par ordre (du plus proche au plus loin) de date de sortie
        $filmsProchain = $this->getDoctrine()->getRepository(Film::class)->getFilmsProchainDate();
        
        $nbFilmsProchain = count($filmsProchain);

        return $this->render('film/prochainement.html.twig', [
            'filmsProchain' => $filmsProchain,
            'nbFilmsProchain' => $nbFilmsProchain
        ]);
    }

}
