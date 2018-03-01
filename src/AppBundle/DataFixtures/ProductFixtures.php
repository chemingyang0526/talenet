<?php 
namespace AppBundle\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Category;
use AppBundle\Entity\Product;

class ProductFixtures extends Fixture
{
	public function load(ObjectManager $manager) {

		$data_string = '{
			"products": [
		    {
		      "name": "Pong",
		      "category": "Games",
		      "sku": "A0001",
		      "price": 69.99,
		      "quantity": 20
		    },
		    {
		      "name": "GameStation 5",
		      "category": "Games",
		      "sku": "A0002",
		      "price": 269.99,
		      "quantity": 15
		    },
		    {
		      "name": "AP Oman PC - Aluminum",
		      "category": "Computers",
		      "sku": "A0003",
		      "price": 1399.99,
		      "quantity": 10
		    },
		    {
		      "name": "Fony UHD HDR 55\" 4k TV",
		      "category": "TVs and Accessories",
		      "sku": "A0004",
		      "price": 1399.99,
		      "quantity": 5
		    }
		    ]
		}';

		$data = json_decode($data_string, true);

		foreach ($data['products'] as $item) {
			$product = $this->createProduct($item['name'], $this->getCat($item['category'], $manager), $item['sku'], $item['price'], $item['quantity']);
			$manager->persist($product);
		}

		$manager->flush();
	}

	private function createProduct($name, $cat, $sku, $price, $quantity) {
		$product = new Product();
		$product->setName($name);
		$product->setCategory($cat);
		$product->setSku($sku);
		$product->setPrice($price);
		$product->setQuantity($quantity);

		return $product;
	}

	private function getCat($cat_name, ObjectManager $manager) {
		$categories = $manager->getRepository('AppBundle:Category');
		$category = $categories->findOneByName($cat_name);

		return $category;
	}
}