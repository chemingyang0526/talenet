<?php

namespace AppBundle\Controller\Api;

use AppBundle\Service\Validate;
use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Product controller.
 */
class ProductController extends Controller
{
    /**
     * lists all product entities via Rest API
     *
     * @Route("/unsecure/product", name="product_index_api")
     * @Method("GET")
     */
    public function indexAction() {

        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAll();

        if (empty($products)) {
            $response = [
                'code' => 1,
                'message' => 'products not found',
                'error' => null,
                'result' => null,
            ];

            return new JsonResponse($response, 204);
        }

        $data = $this->get('jms_serializer')->serialize($products, 'json');
        
        $response = [
            'code' => 0,
            'message' => 'success',
            'errors' => null,
            'result' => json_decode($data),
        ];

        return new JsonResponse($response, 200);
    }


    /**
     * lists one product entities via Rest API
     *
     * @Route("/unsecure/product/{id}", name="show_product_api")
     * @Method("GET")
     */
    public function showAction(Product $product) {
        if (empty($product)) {
            $response = [
                'code' => 1,
                'message' => 'product not found',
                'error' => null,
                'result' => null,
            ];

            return new JsonResponse($response, 204);
        }

        $data = $this->get('jms_serializer')->serialize($product, 'json');
        
        $response = [
            'code' => 0,
            'message' => 'success',
            'errors' => null,
            'result' => json_decode($data),
        ];

        return new JsonResponse($response, 200);
    }

    /**
     * create an product entities via Rest API
     *
     * @Route("/api/product/new", name="new_product_api")
     * @Method("POST")
     */
    public function createAction(Request $request, Validate $validate) {

        $data = $request->getContent();
        $product = $this->get('jms_serializer')->deserialize($data, 'AppBundle\Entity\Product', 'json');
        // set product category
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->findOneById($product->getCategoryId());
        $product->setCategory($category); 

        $response = $validate->validateRequest($product);

        if(!empty($response)) {
            return new JsonResponse($response, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        $data = $this->get('jms_serializer')->serialize($product, 'json');

        $response = [
            'code' => 0,
            'message' => 'success',
            'errors' => null,
            'result' => $data,
        ];

        return new JsonResponse($response, Response::HTTP_CREATED);
    }

    /**
     * update an product entities via Rest API
     *
     * @Route("/api/product/{id}/edit", name="edit_product_api")
     * @Method("PUT")
     */
    public function editAction(Request $request, Validate $validate) {

        $data = $request->getContent();
        $product = $this->get('jms_serializer')->deserialize($data, 'AppBundle\Entity\Product', 'json');
        // set product category
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->findOneById($product->getCategoryId());
        $product->setCategory($category); 

        $response = $validate->validateRequest($product);

        if(!empty($response)) {
            return new JsonResponse($response, Response::HTTP_BAD_REQUEST);
        } 
        $old_product = $em->getRepository('AppBundle:Product')->findOneById($product->getId());

        if (empty($old_product)) {
            $data = $this->get('jms_serializer')->serialize($product, 'json');

            $response = [
                'code' => 1,
                'message' => 'cannot find product to edit',
                'errors' => null,
                'result' => json_decode($data),
            ];
            return new JsonResponse($response, 204);
        }
        $old_product->setName($product->getName());
        $old_product->setSku($product->getSku());
        $old_product->setPrice($product->getPrice());
        $old_product->setQuantity($product->getQuantity());
        $old_product->setCategory($product->getCategory());

        $em = $this->getDoctrine()->getManager();
        $em->persist($old_product);
        $em->flush();

        $data = $this->get('jms_serializer')->serialize($old_product, 'json');

        $response = [
            'code' => 0,
            'message' => 'success',
            'errors' => null,
            'result' => json_decode($data),
        ];

        return new JsonResponse($response, 200);
    }

    /**
     * Deletes a product entity.
     *
     * @Route("api/product/{id}", name="product_delete_api")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product) {
        $data = $request->getContent();
        $product_to_delete = $this->get('jms_serializer')->deserialize($data, 'AppBundle\Entity\Product', 'json');
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->findOneById($product_to_delete->getId());

        if (empty($product)) {
            $data = $this->get('jms_serializer')->serialize($product_to_delete, 'json');

            $response = [
                'code' => 1,
                'message' => 'cannot find product to delete',
                'errors' => null,
                'result' => json_decode($data),
            ];
            return new JsonResponse($response, 204);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $data = $this->get('jms_serializer')->serialize($product, 'json');

        $response = [
            'code' => 0,
            'message' => 'success',
            'errors' => null,
            'result' => json_decode($data),
        ];

        return new JsonResponse($response, 200);
    }
}
