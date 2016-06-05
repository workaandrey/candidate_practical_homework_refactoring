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

class Application extends AbstractGenerator
{
    public function generate($app, array $languages)
    {
        foreach($languages as $lang) {
            echo "[APPLICATION: " . $app . "]\n", "\t[LANGUAGE: " . $lang . "]";
            if ($this->getLanguageFile($app, $lang)) {
                echo " OK\n";
            } else {
                throw new \Exception('Unable to generate language file!');
            }
        }
        
    }

    /**
     * Gets the language file for the given language and stores it.
     *
     * @param string $application   The name of the application.
     * @param string $language      The identifier of the language.
     *
     * @throws CurlException   If there was an error during the download of the language file.
     *
     * @return bool   The success of the operation.
     */
    protected function getLanguageFile($application, $language)
    {
        try {
            $languageResponse = $this->api()->call(
                'system_api',
                'language_api',
                array(
                    'system' => 'LanguageFiles',
                    'action' => 'getLanguageFile'
                ),
                array('language' => $language)
            );
            // If we got correct data we store it.
            $destination = self::getLanguageCachePath($application) . $language . '.php';
            // If there is no folder yet, we'll create it.
            var_dump($destination);
            if (!is_dir(dirname($destination))) {
                mkdir(dirname($destination), 0755, true);
            }

            $result = file_put_contents($destination, $languageResponse['data']);

            return (bool)$result;
        }
        catch (\Exception $e) {
            throw new \Exception('Error during getting language file: (' . $application . '/' . $language . ')');
        }
    }

    /**
     * Gets the directory of the cached language files.
     *
     * @param string $application   The application.
     *
     * @return string   The directory of the cached language files.
     */
    protected static function getLanguageCachePath($application)
    {
        return Config::get('system.paths.root') . '/cache/' . $application. '/';
    }

}