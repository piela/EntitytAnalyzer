<?php

namespace TMSolution\PrototypeBundle\Tests\Util;

use Symfony\Component\Yaml\Yaml;
use TMSolution\PrototypeBundle\Util\ControllerDriver;
use TMSolution\ControllerConfigurationBundle\Util\ControllerConfiguration;
use TMSolution\MapperBundle\Util\ApplicationMapper;
use TMSolution\PrototypeBundle\Sample\SampleLogger;
use TMSolution\PrototypeBundle\Controller\PrototypeController;
use TMSolution\MapperBundle\Util\EntityMapper;
use TMSolution\ConfigurationBundle\Util\Configuration;
use TMSolution\ControllerConfigurationBundle\Util\ControllerConfigurationFactory;
use TMSolution\RequestAnalyzerBundle\Util\RequestAnalyzer;
use Symfony\Component\HttpFoundation\Request;
use \PHPUnit\Framework\TestCase;

/**
 * 
 * php  app/phpunit.phar   --bootstrap=./app/autoload.php ./src/TMSolution/PrototypeBundle/Tests/Util/ControllerDriverTest.php
 * @author Mariusz Piela <mariuszpiela@tmsolution.pl>,TomDyg
 */
class ControllerDriverTest extends TestCase {

    const _ALIAS = 'payment-frequency';
    const _APPLICATION_PATH = 'admin/some/other/path';
    const _ENTITIES_PATH = 'discount/2/measure-unit/3/payment-frequency';
    const _ID = '7';

    protected static $expected_entity_name = 'TMSolution\EntityAnalyzerBundle\Entity\PaymentFrequency';
    static protected $mapperConfiguration;
    static protected $prototypeConfiguration;
    static protected $developerConfiguration;
    static protected $controllerDriver;
    static protected $request;

    public static function setupBeforeClass() {

        $configuration = Yaml::parse(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'testConfiguration.yml'));
        $controllerConfiguration = new ControllerConfiguration($configuration['tm_solution_prototype']['base']);
        self::$controllerDriver = new ControllerDriver($controllerConfiguration);

        self::$request = new Request(['id' => self::_ID], [], [
            'application_path' => self::_APPLICATION_PATH,
            'entities_path' => self::_ENTITIES_PATH
        ]);
        self::$mapperConfiguration = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'testMapper.yml');
        self::$prototypeConfiguration = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'testPrototypeConfiguration.yml');
        self::$developerConfiguration = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'testDeveloperConfiguration.yml');
    }

    protected function createConfiguration() {

        $mapperConfiguration = Yaml::parse(self::$mapperConfiguration);

        $appliactionMapper = new ApplicationMapper($mapperConfiguration['tm_solution_mapper']['applications']);

        $entityMapper = new EntityMapper($mapperConfiguration['tm_solution_mapper']['entities']);

        $requestAnalyzer = new RequestAnalyzer($appliactionMapper, $entityMapper);

        $prototypeConfiguration = Yaml::parse(self::$prototypeConfiguration);
        $developerConfiguration = Yaml::parse(self::$developerConfiguration);

        $baseConfiguration = new Configuration($prototypeConfiguration['tm_solution_prototype']);
        $configurationFactory = new ControllerConfigurationFactory($baseConfiguration, $requestAnalyzer);
        $developerConfiguration = new Configuration($developerConfiguration['tm_solution_prototype']);
        $configurationFactory->addConfiguration($developerConfiguration, self::_APPLICATION_PATH, self::_ALIAS);
        return $configurationFactory->createConfiguration(self::$request, new ControllerConfiguration(), 'new');
    }

    public function testIsActionAllowed() {

        $this->assertTrue(self::$controllerDriver->isActionAllowed());
    }

    public function testGetEntityClass() {

        $configuration = $this->createConfiguration();
        $controllerConfiguration = new ControllerConfiguration($configuration);
        $controllerDriver = new ControllerDriver($controllerConfiguration);
        $this->assertEquals($controllerDriver->getEntityClass(), self::$expected_entity_name);
    }

    public function testGetApplicationPath() {
        
    }

    public function testGetEntitiesPath() {
        
    }

    public function testReturnResultToView() {
        
    }

    public function testGetResultParameter() {
        
    }

    public function testShouldRedirect() {
        
    }

    public function testRedirectionRouteParameters() {
        
    }

    public function testGetModel() {
        
    }

    public function testHasModel() {
        
    }

    public function testFormTypeClass() {
        
    }

    public function testGetFormAction() {
        
    }

    public function testGetTemplate() {
        
    }

}
