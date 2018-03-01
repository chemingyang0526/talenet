<?php
namespace AppBundle\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundatation\Response;

class CategoryControllerTest extends WebTestCase
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

        $lowest_id = $em->createQueryBuilder()
        ->select('MIN(e.id)')
        ->from('AppBundle:Category', 'e')
        ->getQuery()
        ->getSingleScalarResult();

        // fetch all categories
        $crawler = $client->request('GET', '/unsecure/category',[],[], $header);
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /api/category");

        // fetch one category
        $crawler = $client->request('GET', '/unsecure/category/'.$lowest_id,[],[], $header,'');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET api/category/{id}");

        $header = [
            'HTTP_AUTHORIZATION' => "Bearer ".$this->token,
            'CONTENT_TYPE' => 'application/json'
        ];

        // for adding new category
        $data = [
            'name' => "Photography",
        ];

        $crawler = $client->request('POST', '/api/category/new',[],[], $header, json_encode($data));
        $this->assertEquals(201, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for POST /api/category/new");
    
        // for editing a category
        $data = [
            'id' => $lowest_id,
            'name' => "AudioVisual",
        ];

        $crawler = $client->request('PUT', '/api/category/'.$data["id"].'/edit',[],[], $header, json_encode($data));
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for PUT /api/category/{id}/edit");

        $highest_id = $em->createQueryBuilder()
        ->select('MAX(e.id)')
        ->from('AppBundle:Category', 'e')
        ->getQuery()
        ->getSingleScalarResult();

        $data = [
            'id' => $highest_id
        ];

        $crawler = $client->request('DELETE', '/api/category/'.$data["id"],[],[], $header, json_encode($data));
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for DELETE /api/category/{id}");
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
