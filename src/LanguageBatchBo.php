<?php

namespace Language;

/**
 * Business logic related to generating language files.
 */
class LanguageBatchBo
{
	/**
	 * @var Generators\Application
	 */
	private $applicationLngGenerator;

	/**
	 * @var Generators\Applet
	 */
	private $appletLngGenerator;

	/**
	 * LanguageBatchBo constructor.
	 * @param null|Generators\Application $applicationLngGenerator
	 * @param null|Generators\Applet $appletLngGenerator
	 */
	public function __construct($applicationLngGenerator = null, $appletLngGenerator = null)
	{
		
		if(is_null($applicationLngGenerator)) {
			$this->applicationLngGenerator = new Generators\Application();
		}
		if(is_null($appletLngGenerator)) {
			$this->appletLngGenerator = new Generators\Applet();
		}
	}

	/**
	 * Starts the language file generation.
	 *
	 * @return void
	 */
	public function generateLanguageFiles()
	{
		echo "\nGenerating language files\n";
		foreach (Config::get('system.translated_applications') as $app => $languages) {
			$this->applicationLngGenerator->generate($app, $languages);
		}
	}

	public function generateAppletLanguageXmlFiles()
	{
		// List of the applets [directory => applet_id].
		$applets = array(
			'memberapplet' => 'JSM2_MemberApplet',
		);

		echo "\nGetting applet language XMLs..\n";
		foreach ($applets as $appletDirectory => $appletLanguageId) {
			$this->appletLngGenerator->generate($appletDirectory, $appletLanguageId);
		}
		echo "\nApplet language XMLs generated.\n";
	}
}
