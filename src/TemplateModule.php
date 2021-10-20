<?php

namespace app\modules\template;

use ReflectionObject;
use Yii;

/**
 * Template module.
 *
 * @inheritdoc
 * @property-read string $i18nPath the root directory of module translations
 * @property-read string $baseAlias the alias for base directory of this module
 */
class TemplateModule extends \yii\base\Module
{
    /**
     * @var string the unique identifier of this module
     */
    private $uniqueId;
    /**
     * @var string the root directory of module controllers
     */
    private $controllerPath;
    /**
     * @var ReflectionObject the  module reflection instance
     */
    private $reflection;
    /**
     * @var string the  module reflection instance
     */
    private $i18nCategory;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->controllerNamespace === null) {
            $this->controllerNamespace = $this->getReflection()->getNamespaceName() . '\\'
                . (Yii::$app instanceof \yii\web\Application ? 'controllers' : 'commands');
        }
        // Moved down to skip auto set namespace
        parent::init();
        // Add the alias for base directory of this module
        Yii::setAlias($this->getBaseAlias(), $this->getBasePath());
        // Add message translation
        if (is_dir($this->getI18nPath())) {
            Yii::$app->getI18n()->translations[$this->getI18nCategory() . '/*'] = [
                'class' => \yii\i18n\PhpMessageSource::class,
                'sourceLanguage' => Yii::$app->sourceLanguage,
                'basePath' => $this->getI18nPath(),
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function getUniqueId()
    {
        if ($this->uniqueId === null) {
            $this->uniqueId = parent::getUniqueId();
        }

        return $this->uniqueId;
    }

    /**
     * @inheritdoc
     */
    public function getControllerPath()
    {
        if ($this->controllerPath === null) {
            $this->controllerPath = parent::getControllerPath();
        }

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
     * Returns the base category of module translations.
     *
     * @return string
     */
    public function getI18nCategory()
    {
        if ($this->i18nCategory === null) {
            $this->i18nCategory = str_replace('-', '/', $this->getUniqueId());
        }

        return $this->i18nCategory;
    }

    /**
     * Returns the alias for base directory of this module.
     *
     * @return string
     */
    public function getBaseAlias()
    {
        return '@app/modules/' . $this->getUniqueId();
    }

    /**
     * Translates module message, use module identifier as message category.
     *
     * @param string $subCategory the message sub-category
     * @param string $message the module message
     * @param array $params the message parameters
     * @param string|null $language the language name
     * @return string
     * @see Yii::t()
     */
    public function i18n($subCategory, $message, array $params = [], $language = null)
    {
        return Yii::t($this->getI18nCategory() . '/' . $subCategory, $message, $params, $language);
    }

    /**
     * Returns reflection instance for this class.
     *
     * @return ReflectionObject
     */
    protected function getReflection()
    {
        if ($this->reflection === null) {
            $this->reflection = new ReflectionObject($this);
        }
        return $this->reflection;
    }
}
