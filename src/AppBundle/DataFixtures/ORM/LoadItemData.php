<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Item;

class LoadItemData extends AbstractFixture
{
	static public $members = array();

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $item1 = new Item();
        $item1->setName('test_item1');
        $item1->setAmount(0);
        
        
        $item2 = new Item();
        $item2->setName('test_item2');
        $item2->setAmount(4);
        
        $item3 = new Item();
        $item3->setName('test_item3');
        $item3->setAmount(10);
       
        $manager->persist($item1);
        $manager->persist($item2);
        $manager->persist($item3);

        $manager->flush();

        $this->addReference('item-1', $item1);
        $this->addReference('item-2', $item2);
        $this->addReference('item-3', $item3);

        self::$members = array('it1' => $item1, 'it2' =>  $item2, 'it3' =>  $item3);
    }
}