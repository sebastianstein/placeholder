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
     * @return array
     */
    public function getPlaceholderConfiguration(): array
    {
        try {
            $configurationFile = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get(
                'placeholder',
                'configurationFile'
            );
            $fileLoader = GeneralUtility::makeInstance(YamlFileLoader::class);
            return $fileLoader->load($configurationFile);
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

    public function getCkEditorPreset(): string
    {
        $configuration = $this->getPlaceholderConfiguration();

        if (array_key_exists('placeholder', $configuration)) {
            if (array_key_exists('ckEditorPreset', $configuration['placeholder'])) {
                return $configuration['placeholder']['ckEditorPreset'];
            }
            $this->logger->error('Placeholder configuration is missing ckEditorPreset key');
        } else {
            $this->logger->error('Placeholder configuration is missing placeholder key');
        }

        return 'placeholder';
    }

    /**
     * @return array
     */
    public function getPlaceholderFieldConfiguration(): array
    {
        $configuration = $this->getPlaceholderConfiguration();

        if (array_key_exists('placeholder', $configuration)) {
            if (array_key_exists('fieldConfiguration', $configuration['placeholder'])) {
                return $configuration['placeholder']['fieldConfiguration'];
            }

            $this->logger->error('Placeholder configuration is missing fieldConfiguration key');
        } else {
            $this->logger->error('Placeholder configuration is missing placeholder key');
        }

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
}
