<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CustomVariables\tests;

use Piwik\Tests\IntegrationTestCase;

/**
 * @group CustomVariables
 * @group CustomVariablesIntegrationTest
 * @group Database
 */
class CustomVariablesIntegrationTest extends IntegrationTestCase
{
    /**
     * @var Fixtures\VisitWithManyCustomVariables
     */
    public static $fixture = null; // initialized below class definition

    public static function getOutputPrefix()
    {
        return 'CustomVariablesIntegrationTest';
    }

    /**
     * @dataProvider getApiForTesting
     * @group        Integration
     */
    public function testApi($api, $params)
    {
        $this->runApiTests($api, $params);
    }

    public function getApiForTesting()
    {
        $apiToCall = array('CustomVariables.getCustomVariables', 'Live.getLastVisitsDetails');

        return array(
            array($apiToCall, array(
                'idSite'  => self::$fixture->idSite,
                'date'    => self::$fixture->dateTime,
                'periods' => array('day'))
            )
        );
    }

    /**
     * Path where expected/processed output files are stored.
     */
    public static function getPathToTestDirectory()
    {
        return __DIR__;
    }
}

CustomVariablesIntegrationTest::$fixture = new Fixtures\VisitWithManyCustomVariables();