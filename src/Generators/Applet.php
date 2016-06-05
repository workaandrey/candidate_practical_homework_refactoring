<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 03.06.16
 * Time: 16:02
 */

namespace Language\Generators;

use Language\Config;

class Applet extends AbstractGenerator
{
    public function generate($appletDirectory, $appletLanguageId)
    {
        echo " Getting > $appletLanguageId ($appletDirectory) language xmls..\n";
        $languages = $this->getLanguages($appletLanguageId);
        if (empty($languages)) {
            throw new \Exception('There is no available languages for the ' . $appletLanguageId . ' applet.');
        } else {
            echo ' - Available languages: ' . implode(', ', $languages) . "\n";
        }
        foreach ($languages as $language) {
            if ($this->cacheLanguage($appletLanguageId, $language)) {
                echo " OK\n";
            } else {
                throw new \Exception('Unable to generate language file');
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
    
    /**
     * Gets a language xml for an applet.
     *
     * @param string $applet The identifier of the applet.
     * @param string $language The language identifier.
     * @return string|false   The content of the language file or false if weren't able to get it.
     * @throws \Exception
     */
    protected function cacheLanguage($applet, $language)
    {
        $data = $this->getLanguage($applet, $language);
        return $this->save( $language, $data);
    }

    /**
     * @param string $applet
     * @param string $language
     * @return mixed
     * @throws \Language\Exceptions\ApiCall\NoResponse
     * @throws \Language\Exceptions\ApiCall\WrongContent
     * @throws \Language\Exceptions\ApiCall\WrongResponse
     */
    protected function getLanguage($applet, $language)
    {
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

    /**
     * @param $language
     * @param $data
     * @return bool
     */
    protected function save($language, $data)
    {
        $destination = Config::get('system.paths.root') . '/cache/flash' . '/lang_' . $language . '.xml';
        return $this->storage()->put($destination, $data);
    }

}