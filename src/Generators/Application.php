<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 03.06.16
 * Time: 16:02
 */

namespace Language\Generators;


use Language\Config;

class Application extends AbstractGenerator
{
    /**
     * @param $app
     * @param array $languages
     * @throws \Exception
     */
    public function generate($app, array $languages)
    {
        foreach ($languages as $lang) {
            echo "[APPLICATION: " . $app . "]\n", "\t[LANGUAGE: " . $lang . "]";
            if ($this->cacheLanguage($app, $lang)) {
                echo " OK\n";
            } else {
                throw new \Exception('Unable to generate language file!');
            }
        }

    }

    /**
     * Gets the language file for the given language and stores it.
     *
     * @param string $application The name of the application.
     * @param string $language The identifier of the language.
     * @return bool
     * @throws \Exception
     */
    protected function cacheLanguage($application, $language)
    {
        $data = $this->getLanguage($language);
        return $this->save($application, $language, $data);
    }

    /**
     * @param string $language
     * @return mixed
     * @throws \Language\Exceptions\ApiCall\NoResponse
     * @throws \Language\Exceptions\ApiCall\WrongContent
     * @throws \Language\Exceptions\ApiCall\WrongResponse
     */
    protected function getLanguage($language)
    {
        $result = $this->api()->call(
            'system_api',
            'language_api',
            array(
                'system' => 'LanguageFiles',
                'action' => 'getLanguageFile'
            ),
            array('language' => $language)
        );
        return $result['data'];
    }

    /**
     * @param $application
     * @param $language
     * @param $data
     * @return bool
     */
    protected function save($application, $language, $data)
    {
        $destination = Config::get('system.paths.root') . '/cache/' . $application . '/' . $language . '.php';
        return $this->storage()->put($destination, $data);
    }

}