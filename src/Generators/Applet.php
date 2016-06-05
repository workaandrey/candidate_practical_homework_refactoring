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

class Applet
{
    public function generate($appletDirectory, $appletLanguageId)
    {
        echo " Getting > $appletLanguageId ($appletDirectory) language xmls..\n";
        $languages = self::getAppletLanguages($appletLanguageId);
        if (empty($languages)) {
            throw new \Exception('There is no available languages for the ' . $appletLanguageId . ' applet.');
        }
        else {
            echo ' - Available languages: ' . implode(', ', $languages) . "\n";
        }
        $path = Config::get('system.paths.root') . '/cache/flash';
        foreach ($languages as $language) {
            $xmlContent = self::getAppletLanguageFile($appletLanguageId, $language);
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
     * @param string $applet   The applet identifier.
     *
     * @return array   The list of the available applet languages.
     */
    protected static function getAppletLanguages($applet)
    {
        $result = ApiCall::call(
            'system_api',
            'language_api',
            array(
                'system' => 'LanguageFiles',
                'action' => 'getAppletLanguages'
            ),
            array('applet' => $applet)
        );

        try {
            self::checkForApiErrorResult($result);
        }
        catch (\Exception $e) {
            throw new \Exception('Getting languages for applet (' . $applet . ') was unsuccessful ' . $e->getMessage());
        }

        return $result['data'];
    }

    /**
     * Checks the api call result.
     *
     * @param mixed  $result   The api call result to check.
     *
     * @throws Exception   If the api call was not successful.
     *
     * @return void
     */
    protected static function checkForApiErrorResult($result)
    {
        // Error during the api call.
        if ($result === false || !isset($result['status'])) {
            throw new \Exception('Error during the api call');
        }
        // Wrong response.
        if ($result['status'] != 'OK') {
            throw new \Exception('Wrong response: '
                . (!empty($result['error_type']) ? 'Type(' . $result['error_type'] . ') ' : '')
                . (!empty($result['error_code']) ? 'Code(' . $result['error_code'] . ') ' : '')
                . ((string)$result['data']));
        }
        // Wrong content.
        if ($result['data'] === false) {
            throw new \Exception('Wrong content!');
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
    protected static function getAppletLanguageFile($applet, $language)
    {
        $result = ApiCall::call(
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

        try {
            self::checkForApiErrorResult($result);
        }
        catch (\Exception $e) {
            throw new \Exception('Getting language xml for applet: (' . $applet . ') on language: (' . $language . ') was unsuccessful: '
                . $e->getMessage());
        }

        return $result['data'];
    }

}