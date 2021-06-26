<?php

namespace App\Controller\Shared;

use App\Entity\User;
use App\Form\PasswordFormType;
use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController
 * @package App\Controller\Shared
 * @IsGranted("ROLE_USER")
 * @Route("/profile", name="app_profile_")
 */
class ProfileController extends AbstractController
{
    private $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'The profile was updated with success');

            return $this->redirectToRoute('app_profile_index');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/password/update", name="update_password", methods={"GET", "POST"})
     */
    public function updatePassword(Request $request, UserPasswordHasherInterface  $hasher) {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(PasswordFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            $user->setPassword($hasher->hashPassword($user, $plainPassword));

            $this->em->flush();

            $this->addFlash('success', 'The password was updated with success !');

            return $this->redirectToRoute('app_profile_update_password');
        }

        return $this->render('profile/password/index.html.twig', ['form' => $form->createView()]);
    }
}
