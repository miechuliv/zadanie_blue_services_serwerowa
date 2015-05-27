<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Item;
use AppBundle\Form\ItemType;

use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

/**
 * Item controller.
 *
 */
class ItemController extends Controller
{

    /**
     * Get list of items 
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('AppBundle:Item');
        
        $available = $request->get('available');
        $minimalStock = $request->get('minimalStock');
        
        
        
        if($minimalStock)
        {
            $itemList = $repository->getItemsWithAmountAboveOrEqual($minimalStock);
        }
        elseif($available == 'true')
        {
            $itemList = $repository->getAvailableItems();
        }
        elseif($available == 'false')
        {
            $itemList = $repository->getNotAvailableItems();
        }
        else
        {
            $itemList = $repository->findAll();
        }
        
        
        // no items found
        if(!count($itemList))
        {
            
            
            return new JsonResponse(array(
                'message' => 'no items found',
                'data' => array()
            ),404);
        }

        // items found - response OK 200
        $serializer = $this->_getSerializer();
        
        $jsonContent = $serializer->serialize($itemList, 'json');
        
        return new JsonResponse(array(
                'message' => 'items found',
                'data' => $jsonContent
            ),200);
    }
    
    
    private function _getSerializer()
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        
        return $serializer;
    }
    
    /**
     * Creates a new Item entity.
     *
     */
    public function createAction(Request $request)
    {
        
        $name = $request->get('name');
        $amount = $request->get('amount');
        $factory = $this->get('item_factory');
        
        $item = $factory->create($name,$amount);
        
        $validator = $this->get('validator');
        $errors = $validator->validate($item);
        
        if(count($errors) > 0)
        {
            return new JsonResponse(array(
                'message' => 'name and amount parameters are required',
                'params' => array(
                    'name' => $name,
                    'amount' => $amount
                )
                
            ),400);
        }
        else
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($item);
            $em->flush();
            
            return new JsonResponse(array(
                'message' => 'success',
                'id' => $item->getId(),
                
            ),201);
        }
        
        
        

        
    }

    

    

    /**
     * Finds and displays a Item entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Item')->find($id);

        if (!$entity) {
            return new JsonResponse(array(
                'message' => 'no item found for id: '.$id,
                
            ),404);
        }

         $serializer = $this->_getSerializer();
        
        $jsonContent = $serializer->serialize($entity, 'json');
       

        return new JsonResponse(array(
                'message' => 'success',
                'data' => $jsonContent
            ),200);
    }

  

    
    /**
     * Edits an existing Item entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $item = $em->getRepository('AppBundle:Item')->find($id);

        if (!$item) {
            return new JsonResponse(array(
                'message' => 'no item found for id: '.$id,
                
            ),404);
        }

        $name = $request->get('name',NULL);
        if($name !== NULL)
        {
            
             $item->setName($name);
            
        }
        $amount = $request->get('amount',NULL);
        if($amount !== NULL )
        {
            
            $item->setAmount($amount);
            
        }
        
        
        
        $validator = $this->get('validator');
        $errors = $validator->validate($item);

        if (count($errors) > 0) {
            
            return new JsonResponse(array(
                'message' => 'update failed, invalid parameters',
                'params' => array(
                    'name' => $name,
                    'amount' => $amount
                )
                
            ),400);
        }

        
            $em->persist($item);
            $em->flush();
            
            return new JsonResponse(array(
                'message' => 'success',
                'id' => $item->getId(),
                
            ),200);
    }
    /**
     * Deletes a Item entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        

       
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Item')->find($id);

            if (!$entity) {
                    return new JsonResponse(array(
                     'message' => 'no item found for id: '.$id,
                     
                 ),404);
            }

            $em->remove($entity);
            $em->flush();
        

        return new JsonResponse(array(
                     'message' => 'success',
                     
                 ),200);
    }

   
}
