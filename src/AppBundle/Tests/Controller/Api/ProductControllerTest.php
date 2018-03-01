<?php
namespace AppBundle\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundatation\Response;

class ProductControllerTest extends WebTestCase
{
    private $token;

    public function testCompleteScenario() 
    {
        $this->token = $this->getToken();

        $header = [
            'CONTENT_TYPE' => 'application/json'
        ];

        $client = static::createClient();

        $container = $client->getContainer();

        $em = $container->get('doctrine.orm.entity_manager');

        $highest_id = $em->createQueryBuilder()
        ->select('MAX(e.id)')
        ->from('AppBundle:Product', 'e')
        ->getQuery()
        ->getSingleScalarResult();

        $lowest_id = $em->createQueryBuilder()
        ->select('MIN(e.id)')
        ->from('AppBundle:Product', 'e')
        ->getQuery()
        ->getSingleScalarResult();

        $highest_cat = $em->createQueryBuilder()
        ->select('MAX(e.id)')
        ->from('AppBundle:Category', 'e')
        ->getQuery()
        ->getSingleScalarResult();

        // fetch all products
        $crawler = $client->request('GET', '/unsecure/product',[],[], $header);
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /api/product");

        // fetch one product
        $crawler = $client->request('GET', '/unsecure/product/'.$highest_id,[],[], $header,'');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET api/product/{id}");

        $header = [
            'HTTP_AUTHORIZATION' => "Bearer ".$this->token,
            'CONTENT_TYPE' => 'application/json'
        ];

        // for adding new product
        $data = [
            'name' => "BABA BING",
            'category_id' => $highest_cat,
            'sku' => "a10011",
            'price' => 17.77,
            'quantity' => 5,
        ];

        $crawler = $client->request('POST', '/api/product/new',[],[], $header, json_encode($data));
        $this->assertEquals(201, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for POST /api/product/new");
    
        // for editing a product
        $data = [
            'id' => $highest_id,
            'name' => "BADA BING",
            'category_id' => $highest_cat,
            'sku' => "a10025",
            'price' => 21.99,
            'quantity' => 1,
        ];

        $crawler = $client->request('PUT', '/api/product/'.$data["id"].'/edit',[],[], $header, json_encode($data));
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for PUT /api/product/{id}/edit");

        $data = [
            'id' => $lowest_id
        ];

        $crawler = $client->request('DELETE', '/api/product/'.$data["id"],[],[], $header, json_encode($data));
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for DELETE /api/product/{id}");
    }

    private function getToken()
    {
        $client = static::createClient();

        $credentials = [
            'email' => "bobby@foo.com",
            'password' => "secret"
        ];

        $crawler = $client->request('POST', '/api/login_check',[],[],['CONTENT_TYPE' => 'application/json'],json_encode($credentials));

        return json_decode($client->getResponse()->getContent(),true)['token'];
    }
}
