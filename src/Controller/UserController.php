<?php

namespace App\Controller;
use App\Entity\Users;
use App\Form\UserEntityType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Orm\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Flex\TruncatedComposerRepository;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="user")
     */
    public function index()
    {
        return $this->json(["test"]);
    }

    /**
     * @Route("/signIn", name="signin", methods={"POST"})
     */
    public function signIn(Request $request)
    {
        $user = new Users();

        $form = $this->createForm(UserEntityType::class, $user);

        $data = $request->request->all();
        $form->submit($data);

        $user->setCreateAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json([
            "succes"
        ]);
    }

    /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function List()
    {
        $users = $this->getDoctrine()->getRepository(Users::class)->findAll();

        return $this->json($users);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"PUT"})
     */
    public function Edit(Request $request, $id)
    {
        $entity = $this->getDoctrine()->getManager();

        $edit = $entity->getRepository(Users::class)->find($id);
        $data = $request->request->all();
        
        $form = $this->createForm(UserEntityType::class, $edit);
        $form->submit($data);

        $edit->setCreateAt(new \DateTime());


        $em = $this->getDoctrine()->getManager();
        $em->persist($edit);
        $em->flush();
        
        return $this->json($edit);
    }
}
