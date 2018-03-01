<?php

namespace AppBundle\Controller\Api;

use AppBundle\Service\Validate;
use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Category controller.
 */
class CategoryController extends Controller
{
    /**
     * lists all category entities via Rest API
     *
     * @Route("/unsecure/category", name="category_index_api")
     * @Method("GET")
     */
    public function indexAction() {

        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        if (empty($categories)) {
            $response = [
                'code' => 1,
                'message' => 'categories not found',
                'error' => null,
                'result' => null,
            ];

            return new JsonResponse($response, 204);
        }

        $data = $this->get('jms_serializer')->serialize($categories, 'json');
        
        $response = [
            'code' => 0,
            'message' => 'success',
            'errors' => null,
            'result' => json_decode($data),
        ];

        return new JsonResponse($response, 200);
    }

    /**
     * lists one category entities via Rest API
     *
     * @Route("/unsecure/category/{id}", name="show_category_api")
     * @Method("GET")
     */
    public function showAction(Category $category) {
        if (empty($category)) {
            $response = [
                'code' => 1,
                'message' => 'category not found',
                'error' => null,
                'result' => null,
            ];

            return new JsonResponse($response, 204);
        }

        $data = $this->get('jms_serializer')->serialize($category, 'json');
        
        $response = [
            'code' => 0,
            'message' => 'success',
            'errors' => null,
            'result' => json_decode($data),
        ];

        return new JsonResponse($response, 200);
    }

    /**
     * create an category entities via Rest API
     *
     * @Route("/api/category/new", name="new_category_api")
     * @Method("POST")
     */
    public function createAction(Request $request, Validate $validate) {

        $data = $request->getContent();
        $category = $this->get('jms_serializer')->deserialize($data, 'AppBundle\Entity\Category', 'json');

        $response = $validate->validateRequest($category);

        if(!empty($response)) {
            return new JsonResponse($response, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        $data = $this->get('jms_serializer')->serialize($category, 'json');

        $response = [
            'code' => 0,
            'message' => 'success',
            'errors' => null,
            'result' => $data,
        ];

        return new JsonResponse($response, Response::HTTP_CREATED);
    }

    /**
     * update an category entities via Rest API
     *
     * @Route("/api/category/{id}/edit", name="edit_category_api")
     * @Method("PUT")
     */
    public function editAction(Request $request, Validate $validate) {

        $data = $request->getContent();
        $category = $this->get('jms_serializer')->deserialize($data, 'AppBundle\Entity\Category', 'json');
        $response = $validate->validateRequest($category);

        if(!empty($response)) {
            return new JsonResponse($response, Response::HTTP_BAD_REQUEST);
        } 
        
        $em = $this->getDoctrine()->getManager();
        $old_category = $em->getRepository('AppBundle:Category')->findOneById($category->getId());

        if (empty($old_category)) {
            $data = $this->get('jms_serializer')->serialize($category, 'json');

            $response = [
                'code' => 1,
                'message' => 'cannot find category to edit',
                'errors' => null,
                'result' => json_decode($data),
            ];

            return new JsonResponse($response, 204);
        }
        
        $old_category->setName($category->getName());

        $em = $this->getDoctrine()->getManager();
        $em->persist($old_category);
        $em->flush();

        $data = $this->get('jms_serializer')->serialize($old_category, 'json');

        $response = [
            'code' => 0,
            'message' => 'success',
            'errors' => null,
            'result' => json_decode($data),
        ];

        return new JsonResponse($response, 200);
    }

    /**
     * Deletes a category entity.
     *
     * @Route("api/category/{id}", name="category_delete_api")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Category $category) {
        $data = $request->getContent();
        $category_to_delete = $this->get('jms_serializer')->deserialize($data, 'AppBundle\Entity\Category', 'json');
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->findOneById($category_to_delete->getId());

        if (empty($category)) {
            $data = $this->get('jms_serializer')->serialize($category_to_delete, 'json');

            $response = [
                'code' => 1,
                'message' => 'cannot find category to delete',
                'errors' => null,
                'result' => json_decode($data),
            ];
            return new JsonResponse($response, 204);
        }

        $product = $em->getRepository('AppBundle:Product')->findOneByCategory($category);

        if (!empty($product)) {
            $data = $this->get('jms_serializer')->serialize($category, 'json');

            $response = [
                'code' => 1,
                'message' => 'delete prevented by data integrity constraint',
                'errors' => null,
                'result' => json_decode($data),
            ];

            return new JsonResponse($response, 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        $data = $this->get('jms_serializer')->serialize($category, 'json');

        $response = [
            'code' => 0,
            'message' => 'success',
            'errors' => null,
            'result' => json_decode($data),
        ];

        return new JsonResponse($response, 200);
    }
}
