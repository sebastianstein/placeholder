<?php

declare(strict_types=1);

namespace SebastianStein\Placeholder\Utility;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PlaceholderConfigurationUtility implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var array
     */
    private $configuration;

    public function __construct()
    {
        $this->configuration = $this->setPlaceholderConfiguration();
    }

    /**
     * @return array
     */
    public function getPlaceholderConfiguration(): array
    {
        return $this->configuration;
    }

    public function getCkEditorPreset(): string
    {
        if (array_key_exists('ckEditorPreset', $this->configuration['placeholder'])) {
            return $this->configuration['placeholder']['ckEditorPreset'];
        }

        $this->logger->error('Placeholder configuration is missing ckEditorPreset key');
        return 'placeholder';
    }

    /**
     * @return array
     */
    public function getPlaceholderFieldConfiguration(): array
    {
        if (array_key_exists('fieldConfiguration', $this->configuration['placeholder'])) {
            return $this->configuration['placeholder']['fieldConfiguration'];
        }

        $this->logger->error('Placeholder configuration is missing fieldConfiguration key');
        return [];
    }

    public function existPlaceholderFieldConfigurationKey(string $key): bool
    {
        if (array_key_exists($key, $this->getPlaceholderFieldConfiguration())) {
            return true;
        }

        return false;
    }

    public function getPlaceholderFieldConfigurationByKey(string $key): array
    {
        if ($this->existPlaceholderFieldConfigurationKey($key)) {
            return $this->getPlaceholderFieldConfiguration()[$key];
        }

        return [];
    }

    public function isTcaTypePlaceholderEnabled(): bool
    {
        return array_key_exists('enableTcaTypePlaceholder', $this->configuration['placeholder']) &&
            $this->configuration['placeholder']['enableTcaTypePlaceholder'];
    }

    public function isRtePluginEnabled(): bool
    {
        return array_key_exists('enableRtePlugin', $this->configuration['placeholder']) &&
            $this->configuration['placeholder']['enableRtePlugin'];
    }

    private function setPlaceholderConfiguration(): array
    {
        try {
            $configurationFile = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get(
                'placeholder',
                'configurationFile'
            );

            $configuration = GeneralUtility::makeInstance(YamlFileLoader::class)->load($configurationFile);

            if (!array_key_exists('placeholder', $configuration)) {
                $this->logger->error('Placeholder configuration is missing placeholder key');
                return [];
            }

            return $configuration;
        } catch (ExtensionConfigurationExtensionNotConfiguredException $e) {
            $this->logger->error(
                'No extension configuration for extension placeholder found'
            );
        } catch (ExtensionConfigurationPathDoesNotExistException $e) {
            $this->logger->error(
                'Extension path for placeholder not found'
            );
        }

        return [];
    }
}
