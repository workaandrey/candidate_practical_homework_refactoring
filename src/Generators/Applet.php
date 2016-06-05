<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 03.06.16
 * Time: 16:02
 */

namespace Language\Generators;

use Language\ApiCall;
use Language\Config;

class Applet extends AbstractGenerator
{
    public function generate($appletDirectory, $appletLanguageId)
    {
        echo " Getting > $appletLanguageId ($appletDirectory) language xmls..\n";
        $languages = $this->getLanguages($appletLanguageId);
        if (empty($languages)) {
            throw new \Exception('There is no available languages for the ' . $appletLanguageId . ' applet.');
        }
        else {
            echo ' - Available languages: ' . implode(', ', $languages) . "\n";
        }
        $path = Config::get('system.paths.root') . '/cache/flash';
        foreach ($languages as $language) {
            $xmlContent = $this->getLanguageFile($appletLanguageId, $language);
            $xmlFile    = $path . '/lang_' . $language . '.xml';
            if (strlen($xmlContent) == file_put_contents($xmlFile, $xmlContent)) {
                echo " OK saving $xmlFile was successful.\n";
            }
            else {
                throw new \Exception('Unable to save applet: (' . $appletLanguageId . ') language: (' . $language
                    . ') xml (' . $xmlFile . ')!');
            }
        }
        echo " < $appletLanguageId ($appletDirectory) language xml cached.\n";
    }
    
    /**
     * Gets the available languages for the given applet.
     * 
     * @param string $applet The applet identifier.
     * @return array The list of the available applet languages.
     * @throws \Exception
     */
    protected function getLanguages($applet)
    {
        try {
            $result = $this->api()->call(
                'system_api',
                'language_api',
                array(
                    'system' => 'LanguageFiles',
                    'action' => 'getAppletLanguages'
                ),
                array('applet' => $applet)
            );
            return $result['data'];
        }
        catch (\Exception $e) {
            throw new \Exception('Getting languages for applet (' . $applet . ') was unsuccessful ' . $e->getMessage());
        }
    }

    /**
     * Gets a language xml for an applet.
     *
     * @param string $applet      The identifier of the applet.
     * @param string $language    The language identifier.
     *
     * @return string|false   The content of the language file or false if weren't able to get it.
     */
    protected function getLanguageFile($applet, $language)
    {
        try {
            $result = $this->api()->call(
                'system_api',
                'language_api',
                array(
                    'system' => 'LanguageFiles',
                    'action' => 'getAppletLanguageFile'
                ),
                array(
                    'applet' => $applet,
                    'language' => $language
                )
            );
            return $result['data'];
        }
        catch (\Exception $e) {
            throw new \Exception('Getting language xml for applet: (' . $applet . ') on language: (' . $language . ') was unsuccessful: '
                . $e->getMessage());
        }
    }

}