<?php

namespace SimpleSAML\Module\silauth\Auth\Source\config;

use SimpleSAML\Module\silauth\Auth\Source\text\Text;
use yii\base\InvalidConfigException;
use yii\console\Application as ConsoleApplication;
use yii\web\Application as WebApplication;

class ConfigManager
{
    const SEPARATOR = '.';

    /**
     * Get the SimpleSamlPHP config.
     *
     * @return array
     */
    public static function getSspConfig(): array
    {
        return require __DIR__ . '/ssp-config.php';
    }

    /**
     * Just get the SimpleSamlPHP config data for the specified category
     * (eg. 'ldap').
     *
     * @param string $category The category.
     * @return array The config entries for that category. NOTE: The config
     *     prefix will have been removed, so 'mysql.database' will be returned
     *     as 'database', etc.
     */
    public static function getSspConfigFor(string $category): array
    {
        return self::getConfigFor($category, self::getSspConfig());
    }

    /**
     * Get only the config data for the specified category (eg. 'ldap'),
     * extracting it from the given config.
     *
     * @param string $category The category.
     * @param array $config The config to extract the data from.
     * @return array The config entries for that category. NOTE: The config
     *     prefix will have been removed, so 'mysql.database' will be returned
     *     as 'database', etc.
     */
    public static function getConfigFor(string $category, array $config): array
    {
        $categoryPrefix = $category . self::SEPARATOR;
        $categoryConfig = [];
        foreach ($config as $key => $value) {
            if (Text::startsWith($key, $categoryPrefix)) {
                $subKey = self::removeCategory($key);
                $categoryConfig[$subKey] = $value;
            }
        }
        return $categoryConfig;
    }

    /**
     * Get the Yii2 config, merging in the given custom config data.
     *
     * @param array $customConfig
     * @return array
     */
    public static function getMergedYii2Config(array $customConfig): array
    {
        $defaultConfig = require __DIR__ . '/yii2-config.php';
        return array_replace_recursive(
            $defaultConfig,
            $customConfig
        );
    }

    private static function initializeYiiClass(): void
    {
        if (!class_exists('Yii')) {
            require_once __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
        }
    }

    /**
     * @throws InvalidConfigException
     */
    public static function getYii2ConsoleApp(array $customConfig): ConsoleApplication
    {
        self::initializeYiiClass();
        $mergedYii2Config = self::getMergedYii2Config($customConfig);
        return new ConsoleApplication($mergedYii2Config);
    }

    /**
     * @throws InvalidConfigException
     */
    public static function initializeYii2WebApp(array $customConfig = []): void
    {
        self::initializeYiiClass();

        /* Initialize the Yii web application. Note that we do NOT call run()
         * here, since we don't want Yii to handle the HTTP request. We just
         * want the Yii classes available for use (including database
         * models).  */
        $app = new WebApplication(self::getMergedYii2Config($customConfig));

        /*
         * Initialize the Yii logger. It doesn't want to initialize itself for some reason.
         */
        $app->log->getLogger();
    }

    public static function removeCategory(?string $key): bool|string|null
    {
        if ($key === null) {
            return null;
        }
        $pieces = explode(self::SEPARATOR, $key, 2);
        return end($pieces);
    }
}
