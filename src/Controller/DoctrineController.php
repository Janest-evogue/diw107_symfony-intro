<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DoctrineController
 * @package App\Controller
 *
 * @Route("/doctrine")
 */
class DoctrineController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('doctrine/index.html.twig', [
            'controller_name' => 'DoctrineController',
        ]);
    }

    /**
     * @Route("/user/{id}", requirements={"id": "\d+"})
     */
    public function getOneUser(UserRepository $repository, $id)
    {
        /*
         * Retourne un objet User dont les attributs sont settés
         * à partir de la bdd dans la table user à l'id passé en paramètre
         * ou null si l'id n'existe pas dans la table
         */
        $user = $repository->find($id);

        dump($user);

        // si l'id n'existe pas dans la table
        if (is_null($user)) {
            throw new NotFoundHttpException();
        }

        return $this->render(
            'doctrine/get_user.html.twig',
            [
                'user' => $user
            ]
        );
    }

    /**
     * @Route("/users-list")
     */
    public function listUsers(UserRepository $repository)
    {
        /*
         * Retourne tous les utilisateurs de la table user
         * sous forme d'un tableau d'objets User
         */
        //users = $repository->findAll();

        // avec un tri sur le pseudo :
        $users = $repository->findBy([], ['pseudo' => 'ASC']);

        dump($users);

        return $this->render(
            'doctrine/list_users.html.twig',
            [
                'users' => $users
            ]
        );
    }

    /**
     * @Route("/search-email")
     */
    public function searchEmail(Request $request, UserRepository $repository)
    {
        $twigVariables = [];

        // if (isset($_GET['email']))
        if ($request->query->has('email')) {
            // findOneBy quand on est sûr qu'il n'y aura pas plus d'un résultat
            // Retourne un objet User ou null
            $user = $repository->findOneBy([
                'email' => $request->query->get('email')
            ]);

            $twigVariables['user'] = $user;
        }

        return $this->render(
            'doctrine/search_email.html.twig',
            $twigVariables
        );
    }

    /**
     * @Route("/pseudo/user/{pseudo}")
     */
    public function getByPseudo(UserRepository $repository, $pseudo)
    {
        $users = $repository->findBy([
            'pseudo' => $pseudo
        ]);

        return $this->render(
            'doctrine/list_users.html.twig',
            ['users' => $users]
        );
    }

    /**
     * @Route("/create-user")
     */
    public function createUser(Request $request, EntityManagerInterface $manager)
    {
        // si le formulaire a été envoyé
        if ($request->isMethod('POST')) {
            // $data contient tout $_POST
            $data = $request->request->all();

            $user = new User();

            $user
                ->setPseudo($data['pseudo'])
                ->setEmail($data['email'])
                // le setter de birthdate attend un objet DateTime
                ->setBirthdate(new \DateTime($data['birthdate']))
            ;

            // indique au gestionnaire d'entité qu'il faudra enregister le User
            // en bdd au prochain flush
            $manager->persist($user);
            // enregistrement effectif
            $manager->flush();
        }

        return $this->render('doctrine/create_user.html.twig');
    }

    /**
     * @Route("/search")
     */
    public function search(Request $request, UserRepository $repository)
    {
        $twigVariables = [];

        if ($request->query->has('search')) {
            // cf méthode définie dans UserRepository
            $users = $repository->findByPseudoOrEmail($request->query->get('search'));

            $twigVariables['users'] = $users;
        }

        return $this->render(
            'doctrine/search.html.twig',
            $twigVariables
        );
    }
}
