<?php
/**
 * Class TestContainer
 *
 * @filesource   TestContainer.php
 * @created      28.08.2018
 * @package      chillerlan\SettingsTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\SettingsTest;

use chillerlan\Settings\SettingsContainerAbstract;

/**
 * @property $test1
 * @property $test2
 * @property $testConstruct
 * @property $test4
 */
class TestContainer extends SettingsContainerAbstract{
	use TestOptionsTrait;

	private $test3 = 'what';
}
