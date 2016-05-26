<?php
namespace Tests\AppBundle\Functional\Controller;

use FOS\RestBundle\Util\Codes;
use Tests\AppBundle\DataFixtures\ORM\LoadOneTagData;
use Tests\AppBundle\DataFixtures\ORM\LoadTagData;

class TagControllerTest extends MainController
{
    /**
     * @return array
     */
   private function getRawTagData(){
       return array(
           'tag' => array(
               'name' => ''
           )
       );
   }

   public function testTagsAction() {
       $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadTagData');
       $this->loadFixtures($fixtures);

       $entities = $this->getJson('/tags');

       $acutal = count($entities->items) > 0;

       $this->assertEquals(true, $acutal);
   }

    public function testDeleteTagAction() {
        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadTagData');
        $this->loadFixtures($fixtures);

        $entity = LoadTagData::$tags[0];
        $url = sprintf("/tag/%d", $entity->getId());

        $data = $this->deleteJson($url);

        $this->assertEquals(Codes::HTTP_OK, $data->statusCode);
    }

    public function testCreateTagAction(){

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'test-create-tag';

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $data = $this->postJson('/tag/create', $entityJson);
        
        $this->assertEquals(Codes::HTTP_OK, $data->statusCode);
    }

    public function testCreateTagEmptyNameAction(){
        
        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $data = $this->postJson('/tag/create', $entityJson);
        
        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }

    public function testCreateTagUnvaildMinAction(){
        
        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'te';

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $data = $this->postJson('/tag/create', $entityJson);

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }

    public function testCreateTagUnvaildMaxAction(){

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'Lorem ipsum dolor sit amet, com';

        $entityJson = $serializer->serialize($entityRaw, 'json');
        
        $data = $this->postJson('/tag/create', $entityJson);

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }

    public function testCreateTagUniqueAction() {
        
        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadOneTagData');
        $this->loadFixtures($fixtures);

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'test-tag';

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $data = $this->postJson('/tag/create', $entityJson);

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }

    public function testCreateTagBadFormatAction() {

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityJson = $serializer->serialize(array(), 'json');

        $data = $this->postJson('/tag/create', $entityJson);

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }

    public function testUpdateTagAction() {

        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadOneTagData');
        $this->loadFixtures($fixtures);
        
        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'test-tag-update';
        $entityRaw['tag']['id'] = LoadOneTagData::$entity->getId();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $data = $this->patchJson('/tag/update', $entityJson);

        $this->assertEquals(Codes::HTTP_OK, $data->statusCode);
    }

    public function testUpdateTagEntityNotFoundAction() {

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'test-tag-update';
        $entityRaw['tag']['id'] = '1';

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $data = $this->patchJson('/tag/update', $entityJson);

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }

    public function testUpdateTagUnvaildMinAction() {

        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadOneTagData');
        $this->loadFixtures($fixtures);

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'lo';
        $entityRaw['tag']['id'] = LoadOneTagData::$entity->getId();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $data = $this->patchJson('/tag/update', $entityJson);

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }

    public function testUpdateTagUnvaildMaxAction() {

        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadOneTagData');
        $this->loadFixtures($fixtures);
        
        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'Lorem ipsum dolor sit amet, com';
        $entityRaw['tag']['id'] = LoadOneTagData::$entity->getId();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $data = $this->patchJson('/tag/update', $entityJson);

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }

    public function testUpdateTagUniqueAction() {

        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadTagData');
        $this->loadFixtures($fixtures);

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'GOlang';
        $entityRaw['tag']['id'] = LoadTagData::$tags[0]->getId();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $data = $this->patchJson('/tag/update', $entityJson);

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }
}