<?php 
namespace AppBundle\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Category;

class CategoryFixtures extends Fixture
{
	public function load(ObjectManager $manager) {

		$data = [
			'Games',
			'Computers',
			'TVs and Accessories'
		];

		// create objects
		foreach ($data as $item) {
			$category = $this->createCategory($item);
			$manager->persist($category);
		}
		$manager->flush();
	}

	private function createCategory($name):Category {
		$category = new Category();
		$category->setName($name);

		return $category;
	}
}