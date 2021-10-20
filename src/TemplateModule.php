<?php

namespace app\modules\template;

use Yii;

/**
 * Template module.
 *
 * @inheritdoc
 * @property-read string $i18nPath the root directory of module translations
 */
class TemplateModule extends \yii\base\Module
{
    /**
     * @var string|null the root directory of module controllers
     */
    protected $controllerPath;

    /**
     * @inheritdoc
     */
    public function init()
    {
        // Moved up to skip auto detecting namespace
        if (Yii::$app instanceof \yii\web\Application) {
            $this->controllerNamespace = __NAMESPACE__ . '\controllers';
            $this->controllerPath = __DIR__ . DIRECTORY_SEPARATOR . 'controllers';
        } else {
            $this->controllerNamespace = static::NS . '\commands';
            $this->controllerPath = __DIR__ . DIRECTORY_SEPARATOR . 'commands';
        }

        parent::init();

        if (is_dir($this->getI18nPath())) {
            $this->addTranslation();
        }
    }

    /**
     * @inheritdoc
     */
    public function getControllerPath()
    {
        return $this->controllerPath;
    }

    /**
     * Returns the root directory of module translations.
     *
     * @return string
     */
    public function getI18nPath()
    {
        return $this->getBasePath() . DIRECTORY_SEPARATOR . 'messages';
    }

    /**
     * Translates module message, use module identifier as message category.
     *
     * @param string $message the module message
     * @param array $params the message parameters
     * @param string|null $language the language name
     * @return string
     * @see Yii::t()
     */
    public function i18n($message, array $params = [], $language = null)
    {
        return Yii::t($this->getUniqueId(), $message, $params, $language);
    }

    /**
     * Adds translation for this module.
     *
     * @return void
     * @see \yii\i18n\I18N::$translations
     */
    protected function addTranslation()
    {
        Yii::$app->getI18n()->translations[$this->getUniqueId()] = [
            'class' => \yii\i18n\PhpMessageSource::class,
            'sourceLanguage' => Yii::$app->sourceLanguage,
            'basePath' => $this->getI18nPath(),
        ];
    }
}
