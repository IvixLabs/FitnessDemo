<?php

namespace App\Controller;

use App\Dto\UserCreateDto;
use App\Dto\UserUpdateDto;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Form\UserCreateType;
use App\Form\UserUpdateType;
use App\Manager\TransactionManager;
use App\Repository\UserRepositoryInterface;
use App\Security\Role;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller handles crud operations of user for admin
 *
 * @Route("/api/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/list.json", name="user_list", methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     *
     * @return array
     */
    public function listAction(
        UserRepositoryInterface $repository,
        Request $request
    ): array {
        $start = $request->get('start');
        $limit = $request->get('limit');
        $filters = $request->get('filters');
        $sorting = $request->get('sorting');
        return $repository->findList($start, $limit, json_decode($filters, true), json_decode($sorting, true));
    }

    /**
     * @Route("/create.json", name="user_create",methods="POST", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function createAction(
        Request $request,
        UserFactory $userFactory,
        UserManagerInterface $userManager
    ) {
        $userDto = new UserCreateDto();
        $form = $this->createForm(UserCreateType::class, $userDto, ['csrf_protection' => false]);
        $json = json_decode($request->getContent(), true);
        $form->submit($json);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userFactory->create($userDto);
            $user->setEnabled(true);
            $user->setSuperAdmin(false);

            $userManager->updateUser($user);

            return new UserUpdateDto($user);
        }

        return $form;
    }

    /**
     * @Route("/get.json", name="user_get",methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function getAction(UserRepositoryInterface $userRepository, Request $request): UserUpdateDto
    {
        $id = $request->get('id');
        $entity = $userRepository->getById($id);
        return new UserUpdateDto($entity);
    }

    /**
     * @Route("/form.json", name="user_form",methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function formAction(Request $request)
    {
        return [
            'roles' => [Role::ROLE_ADMIN, Role::ROLE_FITNESS_CLIENT],
        ];
    }

    /**
     * @Route("/update.json", name="user_update", methods="POST", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function updateAction(UserRepositoryInterface $userRepository, UserManagerInterface $userManager, Request $request)
    {
        $id = $request->get('id');
        $entity = $userRepository->find($id);
        $entityDto = new UserUpdateDto($entity);

        $form = $this->createForm(UserUpdateType::class, $entityDto, ['csrf_protection' => false]);
        $json = json_decode($request->getContent(), true);
        $form->submit($json);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->updateUser($entity);
            return $entityDto;
        }

        return $form;
    }

    /**
     * @Route("/delete.json", name="user_delete", methods="DELETE", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function deleteAction(
        UserRepositoryInterface $repository,
        TransactionManager $transactionManager,
        Request $request
    ) {
        $id = $request->get('id');

        /** @var User $user */
        $entity = $this->getUser();
        if ($entity->getId() == $id) {
            throw new \Exception('Impossible remove himself');
        }
        $entity = $repository->find($id);

        $transactionManager->begin();
        $repository->remove($entity);
        $transactionManager->end();
    }
}
