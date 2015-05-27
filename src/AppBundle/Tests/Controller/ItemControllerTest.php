<?php
namespace AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use AppBundle\DataFixtures\ORM\LoadItemData as LoadItemData;

class ItemControllerTest extends WebTestCase {

    

    public function setUp(){
        $this->client = static::createClient();
    }

    protected function assertJsonResponse($response, $statusCode = 200) {
            $this->assertEquals(
                $statusCode, $response->getStatusCode(),
                $response->getContent()
            );
            $this->assertTrue(
                $response->headers->contains('Content-Type', 'application/json'),
                $response->headers
            );
    }

    /**
     * W rzeczywistej aplikacji napisal bym wiecej testow sprawdzajacych wszystkie 
     * funkcjonalnosci
     */
    public function testAllAction() {
        $fixtures = array('AppBundle\DataFixtures\ORM\LoadItemData');
        
        $this->loadFixtures($fixtures);
        $members = LoadItemData::$members;
        
      
        
            $route =  $this->getUrl('item', array('available' => 'true'  ));

            $this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
            
            $response = $this->client->getResponse();
            $content = $response->getContent();

            $this->assertJsonResponse($response, 200);
            
            $content = json_decode($response->getContent(),TRUE);
            
            
            $this->assertEquals(json_decode($content['data'],TRUE), array(
                array(
                    'id' => $members['it2']->getId(),
                    'name' => 'test_item2',
                    'amount' => 4
                ),
                array(
                    'id' => $members['it3']->getId(),
                    'name' => 'test_item3',
                    'amount' => 10
                ),
            ));
        
    }


    
}