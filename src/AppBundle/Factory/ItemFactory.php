<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace AppBundle\Factory;
use AppBundle\Entity\Item;
/**
 * Description of ItemFactory
 *
 * @author miechuliv
 */
class ItemFactory {
    //put your code here
    
    public function create($name,$amount)
    {
        $item = new Item();
        
        $item->setName($name);
        $item->setAmount($amount);
        
        return $item;
    }
}
