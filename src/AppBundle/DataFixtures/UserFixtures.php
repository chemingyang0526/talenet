<?php 
namespace AppBundle\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
	private $container;

	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}

	public function load(ObjectManager $manager) {
		
		$data_string = '{
			"users": [
				{
					"name": "Bobby Fischer",
					"email": "bobby@foo.com"
				},
				{
					"name": "Betty Rubble",
					"email": "betty@foo.com"
				}
			]
		}';

		$data = json_decode($data_string, true);

		$plainPassword = 'secret';
		
		foreach ($data['users'] as $item) {
			$user = $this->createActiveUser($item['name'], $item['email'], $plainPassword);
			$manager->persist($user);
		}
		$manager->flush();
	}

	public function getDependencies(){

		return [
			CategoryFixtures::class,
		];
	}

	private function createActiveUser($name, $email, $plainPassword):User {
		$user = new User();
		$user->setName($name);
		$user->setEmail($email);
		$user->setIsactive(true);
		// password - to be hashed
		$encodedPassword = $this->encodePassword($user, $plainPassword);
		$user->setPassword($encodedPassword);
		return $user;
	}

	private function encodePassword($user, $plainPassword):string {
		$encoder = $this->container->get('security.password_encoder');
		$encodedPassword = $encoder->encodePassword($user, $plainPassword);
		return $encodedPassword;
	}
}