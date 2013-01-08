<?php
namespace Noma\NomaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

abstract class FunctionalTestCase extends WebTestCase {

    protected $client;
    protected $entityManager;

    public function __construct($name=NULL, array $data=array())
    {
        parent::__construct($name, $data);
        $this->initialize();
    }

    protected static function initialize() {
        self::createClient();
        $application = new Application(static::$kernel);
        $application->setAutoExit(false);

        self::createDatabase($application);
    }

    private static function createDatabase($application) {
        self::executeCommand($application, "doctrine:database:drop", array("--force" => true));
        self::executeCommand($application, "doctrine:database:create");
        self::executeCommand($application, "doctrine:schema:create");
        self::executeCommand($application, "doctrine:fixtures:load", array("--fixtures" => __DIR__ . "/../DataFixtures/ORM/test"));
    }

    private static function executeCommand($application, $command, Array $options = array()) {
        $options["-e"] = "test";
        $options["-q"] = null;
        $options["-n"] = true;
        $options = array_merge($options, array('command' => $command));
        return $application->run(new ArrayInput($options));
    }

    public function setUp() {
        $this->populateVariables();
    }

    protected function populateVariables() {
        $this->client = static::createClient();
        $container = static::$kernel->getContainer();
        $this->entityManager = $container->get('doctrine')->getEntityManager();
    }
}

