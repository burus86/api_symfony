<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ApiProductController
 * @package App\Controller\Api
 *
 * @Route("/api/product")
 */
class ApiProductController extends AbstractController
{
    use ApiBaseControllerTrait;

    const URL_EXCHANGE_RATES_API = "https://api.exchangeratesapi.io/latest";

    /**
     * Lists all Product entities.
     *
     * @Route("/", methods="GET", name="product_index")
     *
     * @param ProductRepository $productRepository
     * @param TranslatorInterface $translator
     * @return JsonResponse
     */
    public function index(ProductRepository $productRepository, TranslatorInterface $translator): JsonResponse
    {
        $data = [];
        $products = $productRepository->findAll();
        foreach ($products as $product) {
            /** @var Product $product */
            $data[] = $product->toArray();
        }

        return $this->getApiSuccessJsonResponse(array(
            'message' => $translator->trans('message.success.index.product'),
            'data' => $data
        ));
    }

    /**
     * Lists all Product entities filtered by $featured = TRUE.
     *
     * @Route("/featured", methods="GET", name="product_index_featured")
     *
     * @param Request $request
     * @param ProductRepository $productRepository
     * @param TranslatorInterface $translator
     * @param HttpClientInterface $client
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function featured(Request $request, ProductRepository $productRepository, TranslatorInterface $translator, HttpClientInterface $client): JsonResponse
    {
        $currencyConversion = false;
        if ($currency = strtoupper($request->get('currency'))) {
            if (strcmp($currency, Product::CURRENCY_EUR) !== 0 && strcmp($currency, Product::CURRENCY_USD) !== 0) {
                return $this->getApiErrorJsonResponse(array('message' => $translator->trans('message.error.invalidFilter.product', $parameters = array('{{ currency }}' => $currency)) ), $status = Response::HTTP_BAD_REQUEST);
            }
            $currencyConversion = true;
        }
        $data = [];
        $products = $productRepository->findAllByFeatured();
        foreach ($products as $product) {
            if ($currencyConversion && strcmp($product->getCurrency(), $currency) !== 0) {
                $price = $this->getPriceConversion($product, $currency, $client);
                $product->setPrice($price)->setCurrency($currency);
            }
            $data[] = $product->toArray();
        }
        //$this->getDoctrine()->getManager()->flush();

        $message = !$currencyConversion
            ? $translator->trans('message.success.index.productFeatured')
            : $translator->trans('message.success.index.productFeaturedWithConversion', $parameters = array('{{ currency }}' => $currency))
        ;
        return $this->getApiSuccessJsonResponse(array(
            'message' => $translator->trans($message),
            'data' => $data
        ));
    }

    /**
     * Creates a new Product entity.
     *
     * @Route("/", methods="POST", name="product_new")
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
        $product = $this->saveEntity(new Product(), $request);
        $violations = $validator->validate($product);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return $this->getApiErrorJsonResponse(array('message' => $translator->trans('message.error.new.product'), 'errors' => $errors), $status = Response::HTTP_BAD_REQUEST);
        }
        $this->getDoctrine()->getManager()->persist($product);
        $this->getDoctrine()->getManager()->flush();
        return $this->getApiSuccessJsonResponse(array(
            'message' => $translator->trans('message.success.new.product', $parameters = array('{{ id }}' => $product->getId())),
            'data' => $product->toArray(),
        ), $status = Response::HTTP_CREATED);
    }

    /**
     * Finds and displays a Product entity.
     *
     * @Route("/{id}", methods="GET", name="product_show", requirements={"id"="\d+"})
     *
     * @param int $id
     * @param ProductRepository $productRepository
     * @param TranslatorInterface $translator
     * @return JsonResponse
     */
    public function show(int $id, ProductRepository $productRepository, TranslatorInterface $translator): JsonResponse
    {
        /** @var Product $product */
        $product = $productRepository->find($id);
        if (!$product) {
            return $this->getApiErrorJsonResponse(array('message' => $translator->trans('message.error.notFound.product', $parameters = array('{{ id }}' => $id)) ), $status = Response::HTTP_NOT_FOUND);
        }
        return $this->getApiSuccessJsonResponse(array(
            'message' => $translator->trans('message.success.show.product', $parameters = array('{{ id }}' => $id)),
            'data' => $product->toArray()
        ));
    }

    /**
     * Updates a Product entity.
     *
     * @Route("/{id}", methods="PUT", name="product_update", requirements={"id"="\d+"})
     *
     * @param int $id
     * @param Request $request
     * @param ProductRepository $productRepository
     * @param ValidatorInterface $validator
     * @param TranslatorInterface $translator
     * @return JsonResponse
     */
    public function update(int $id, Request $request, ProductRepository $productRepository, ValidatorInterface $validator, TranslatorInterface $translator): JsonResponse
    {
        /** @var Product $product */
        $product = $productRepository->find($id);
        if (!$product) {
            return $this->getApiErrorJsonResponse(array('message' => $translator->trans('message.error.notFound.product', $parameters = array('{{ id }}' => $id)) ), $status = Response::HTTP_NOT_FOUND);
        }
        if (0 !== strpos($request->headers->get('Content-Type'), 'application/json')) {
            return $this->getApiErrorJsonResponse(array('message' => $translator->trans('message.error.invalidContentType')), $status = Response::HTTP_BAD_REQUEST);
        }
        $product = $this->saveEntity($product, $request);
        $violations = $validator->validate($product);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return $this->getApiErrorJsonResponse(array('message' => $translator->trans('message.error.update.product', $parameters = array('{{ id }}' => $id)), 'errors' => $errors), $status = Response::HTTP_BAD_REQUEST);
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->getApiSuccessJsonResponse(array(
            'message' => $translator->trans('message.success.update.product', $parameters = array('{{ id }}' => $id)),
            'data' => $product->toArray(),
        ));
    }

    /**
     * Deletes a Product entity.
     *
     * @Route("/{id}", methods="DELETE", name="product_delete", requirements={"id"="\d+"})
     * @Route("/{id}/delete", methods="GET", requirements={"id"="\d+"})
     *
     * @param int $id
     * @param ProductRepository $productRepository
     * @param TranslatorInterface $translator
     * @return JsonResponse
     * @throws ORMException
     */
    public function delete(int $id, ProductRepository $productRepository, TranslatorInterface $translator): JsonResponse
    {
        /** @var Product $product */
        $product = $productRepository->find($id);
        if (!$product) {
            return $this->getApiErrorJsonResponse(array('message' => $translator->trans('message.error.notFound.product', $parameters = array('{{ id }}' => $id)) ), $status = Response::HTTP_NOT_FOUND);
        }
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        return $this->getApiSuccessJsonResponse(array('message' => $translator->trans('message.success.delete.product', $parameters = array('{{ id }}' => $id))));
    }

    /**
     * @param Product $product
     * @param Request $request
     * @return Product
     */
    private function saveEntity(Product $product, Request $request): Product
    {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
        $category = null;
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Category $category */
        if ($categoryId = $request->get('categoryId')) {
            $category = $em->getRepository(Category::class)->find($categoryId);
        } elseif ($categoryName = $request->get('categoryName')) {
            $category = $em->getRepository(Category::class)->findOneByName($categoryName);
        }
        $featured = $request->get('featured') ? : false;
        $currency = $request->get('currency') ? strtoupper($request->get('currency')) : null;
        $product
            ->setName($request->get('name'))
            ->setPrice($request->get('price'))
            ->setCurrency($currency)
            ->setSerialNumber($request->get('serialNumber'))
            ->setBrand($request->get('brand'))
            ->setFeatured($featured)
            ->setCategory($category)
        ;

        return $product;
    }

    /**
     * @param Product $product
     * @param string $currency
     * @param HttpClientInterface $client
     * @return float
     * @throws ExceptionInterface
     */
    private function getPriceConversion(Product $product, string $currency, HttpClientInterface $client): float
    {
        $response = $client->request('GET', $this->getExchangeRatesApiUrl($originalCurrency = $product->getCurrency(), $currency));
        $content = $response->toArray();
        $rates = isset($content['rates']) && isset($content['rates'][$currency]) ? $content['rates'][$currency] : 1;
        return floatval($product->getPrice() * $rates);
    }

    /**
     * @param string $originalCurrency
     * @param string $currency
     * @return string
     */
    private function getExchangeRatesApiUrl(string $originalCurrency, string $currency): string
    {
        return self::URL_EXCHANGE_RATES_API."?base={$originalCurrency}&symbols={$currency}";
    }
}
