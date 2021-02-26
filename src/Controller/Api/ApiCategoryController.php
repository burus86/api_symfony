<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ApiCategoryController
 * @package App\Controller\Api
 *
 * @Route("/api/category")
 */
class ApiCategoryController extends AbstractController
{
    use ApiBaseControllerTrait;

    /**
     * Lists all Category entities.
     *
     * @Route("/", methods="GET", name="category_index")
     *
     * @param CategoryRepository $categoryRepository
     * @param TranslatorInterface $translator
     * @return JsonResponse
     */
    public function index(CategoryRepository $categoryRepository, TranslatorInterface $translator): JsonResponse
    {
        $data = [];
        $categories = $categoryRepository->findAll();
        foreach ($categories as $category) {
            /** @var Category $category */
            $data[] = $category->toArray();
        }

        return $this->getApiSuccessJsonResponse(array(
            'message' => $translator->trans('message.success.index.category'),
            'data' => $data
        ));
    }

    /**
     * Creates a new Category entity.
     *
     * @Route("/", methods="POST", name="category_new")
     *
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param TranslatorInterface $translator
     * @return JsonResponse
     */
    public function new(Request $request, ValidatorInterface $validator, TranslatorInterface $translator): JsonResponse
    {
        if (0 !== strpos($request->headers->get('Content-Type'), 'application/json')) {
            return $this->getApiErrorJsonResponse(array('message' => $translator->trans('message.error.invalidContentType')), $status = Response::HTTP_BAD_REQUEST);
        }
        $category = $this->saveEntity(new Category(), $request);
        $violations = $validator->validate($category);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return $this->getApiErrorJsonResponse(array('message' => $translator->trans('message.error.new.category'), 'errors' => $errors), $status = Response::HTTP_BAD_REQUEST);
        }
        $this->getDoctrine()->getManager()->persist($category);
        $this->getDoctrine()->getManager()->flush();
        return $this->getApiSuccessJsonResponse(array(
            'message' => $translator->trans('message.success.new.category', $parameters = array('{{ id }}' => $category->getId())),
            'data' => $category->toArray(),
        ), $status = Response::HTTP_CREATED);
    }

    /**
     * Finds and displays a Category entity.
     *
     * @Route("/{id}", methods="GET", name="category_show", requirements={"id"="\d+"})
     *
     * @param int $id
     * @param CategoryRepository $categoryRepository
     * @param TranslatorInterface $translator
     * @return JsonResponse
     */
    public function show(int $id, CategoryRepository $categoryRepository, TranslatorInterface $translator): JsonResponse
    {
        /** @var Category $category */
        $category = $categoryRepository->find($id);
        if (!$category) {
            return $this->getApiErrorJsonResponse(array('message' => $translator->trans('message.error.notFound.category', $parameters = array('{{ id }}' => $id)) ), $status = Response::HTTP_NOT_FOUND);
        }
        return $this->getApiSuccessJsonResponse(array(
            'message' => $translator->trans('message.success.show.category', $parameters = array('{{ id }}' => $id)),
            'data' => $category->toArray()
        ));
    }

    /**
     * Updates a Category entity.
     *
     * @Route("/{id}", methods="PUT", name="category_update", requirements={"id"="\d+"})
     *
     * @param int $id
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @param ValidatorInterface $validator
     * @param TranslatorInterface $translator
     * @return JsonResponse
     */
    public function update(int $id, Request $request, CategoryRepository $categoryRepository, ValidatorInterface $validator, TranslatorInterface $translator): JsonResponse
    {
        /** @var Category $category */
        $category = $categoryRepository->find($id);
        if (!$category) {
            return $this->getApiErrorJsonResponse(array('message' => $translator->trans('message.error.notFound.category', $parameters = array('{{ id }}' => $id)) ), $status = Response::HTTP_NOT_FOUND);
        }
        if (0 !== strpos($request->headers->get('Content-Type'), 'application/json')) {
            return $this->getApiErrorJsonResponse(array('message' => $translator->trans('message.error.invalidContentType')), $status = Response::HTTP_BAD_REQUEST);
        }
        $category = $this->saveEntity($category, $request);
        $violations = $validator->validate($category);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return $this->getApiErrorJsonResponse(array('message' => $translator->trans('message.error.update.category', $parameters = array('{{ id }}' => $id)), 'errors' => $errors), $status = Response::HTTP_BAD_REQUEST);
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->getApiSuccessJsonResponse(array(
            'message' => $translator->trans('message.success.update.category', $parameters = array('{{ id }}' => $id)),
            'data' => $category->toArray(),
        ));
    }

    /**
     * Deletes a Category entity.
     *
     * @Route("/{id}", methods="DELETE", name="category_delete", requirements={"id"="\d+"})
     * @Route("/{id}/delete", methods="GET", requirements={"id"="\d+"})
     *
     * @param int $id
     * @param CategoryRepository $categoryRepository
     * @param TranslatorInterface $translator
     * @return JsonResponse
     * @throws ORMException
     */
    public function delete(int $id, CategoryRepository $categoryRepository, TranslatorInterface $translator): JsonResponse
    {
        /** @var Category $category */
        $category = $categoryRepository->find($id);
        if (!$category) {
            return $this->getApiErrorJsonResponse(array('message' => $translator->trans('message.error.notFound.category', $parameters = array('{{ id }}' => $id)) ), $status = Response::HTTP_NOT_FOUND);
        }
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        if ($category->getProducts()->count() > 0) {
            foreach ($category->getProducts() as $product) {
                $product->setCategory(null); //$em->remove($product);
            }
        }
        $em->remove($category);
        $em->flush();
        return $this->getApiSuccessJsonResponse(array('message' => $translator->trans('message.success.delete.category', $parameters = array('{{ id }}' => $id))));
    }

    /**
     * @param Category $category
     * @param Request $request
     * @return Category
     */
    private function saveEntity(Category $category, Request $request): Category
    {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
        return $category
            ->setName($request->get('name'))
            ->setDescription($request->get('description'));
    }
}
