<?php
/**
*
* @package testing
* @copyright (c) 2014 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbb\boardrules\tests\functional;

/**
* @group functional
*/
class boardrules_controller_test extends \extension_functional_test_case
{
	public function setUp()
	{
		parent::setUp();
		$this->login();
		$this->admin_login();
		$this->set_extension('phpbb', 'boardrules', 'Board Rules');
		$this->enable_extension();
		$this->enable_boardrules();
		$this->add_lang_ext(array('boardrules_common', 'boardrules_controller'));
	}

	/**
	* Board rules installs in a disabled state. We need to turn it on to test it.
	*
	* @access public
	*/
	public function enable_boardrules()
	{
		$this->get_db();

		$sql = "UPDATE phpbb_config
			SET config_value = '1'
			WHERE config_name = 'boardrules_enable'";

		$this->db->sql_query($sql);
	}

	/**
	* Test loading the rules page
	*
	* @access public
	*/
	public function test_boardrules_page()
	{
		$this->logout();
		$crawler = self::request('GET', 'app.php/rules');
	}

	/**
	* Test loading the rules page with some sample data
	*
	* @access public
	*/
	public function test_boardrules_with_data()
	{
		$crawler = self::request('GET', 'app.php/rules');
		$this->assertContains($this->lang('BOARDRULES_HEADER'), $crawler->text());

		$this->assertEquals(1, $crawler->filter('#example-rule-category')->count());
		$this->assertEquals(1, $crawler->filter('#example-rule')->count());
	}

	/**
	* Test for presence of the Rules header link nav
	*
	* @access public
	*/
	public function test_boardrules_header_link()
	{
		$this->logout();
		$crawler = self::request('GET', 'index.php');

		$this->assertContains($this->lang('BOARDRULES'), $crawler->filter('.navbar')->text());
		$this->assertGreaterThan(0, $crawler->filter('.icon-boardrules')->count());
	}
}