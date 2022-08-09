<?php

declare(strict_types=1);

namespace SebastianStein\Placeholder\Event;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use SebastianStein\Placeholder\Utility\PlaceholderConfigurationUtility;
use TYPO3\CMS\Core\Configuration\Event\AfterTcaCompilationEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TcaCompilation implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var PlaceholderConfigurationUtility
     */
    protected $configurationUtility;

    public function __construct()
    {
        $this->configurationUtility =
            GeneralUtility::makeInstance(PlaceholderConfigurationUtility::class);
    }

    /**
     * @param AfterTcaCompilationEvent $event
     *
     */
    public function setPlaceholderFieldsInTca(AfterTcaCompilationEvent $event): void
    {
        $tca = $event->getTca();

        foreach (
            $this->configurationUtility->getPlaceholderFieldConfiguration() as $table => $configuration
        ) {
            if (array_key_exists($table, $tca)) {
                foreach ($configuration as $tcaType => $value) {
                    if (is_array($value)) {
                        // configuration for tca subtypes
                        foreach ($value as $field) {
                            $this->updateTcaField($field, $table, $tca, $tcaType);
                        }
                    } else {
                        $this->updateTcaField($value, $table, $tca);
                    }
                }
            } else {
                $this->logger->error('no TCA configuration for the given table: ' . $table);
            }
        }

        $event->setTca($tca);
    }

    private function updateTcaField(string $field, string $table, &$tca, $tcaType = null): void
    {
        if (array_key_exists($field, $tca[$table]['columns'])) {
            $type = $tca[$table]['columns'][$field]['config']['type'];

            switch ($type) {
                case 'text':
                    if ($this->configurationUtility->isRtePluginEnabled()) {
                        if ($tca[$table]['types'][$tcaType]['columnsOverrides'][$field]['config']['enableRichtext'] ||
                            $tca[$table]['columns'][$field]['config']['enableRichtext']) {
                            // RTE
                            $tca[$table]['types'][$tcaType]['columnsOverrides'][$field]['config']['richtextConfiguration'] =
                                $this->configurationUtility->getCkEditorPreset();
                        } else {
                            // Textarea
                        }
                    }
                    break;
                case 'input':
                    if ($this->configurationUtility->isTcaTypePlaceholderEnabled()) {
                        if (!is_null($tcaType)) {
                            $tca[$table]['types'][$tcaType]['columnsOverrides'][$field]['config']['type'] = 'user';
                            $tca[$table]['types'][$tcaType]['columnsOverrides'][$field]['config']['renderType'] =
                                'placeholderInput';
                        } else {
                            $tca[$table]['columns'][$field]['config']['type'] = 'user';
                            $tca[$table]['columns'][$field]['config']['renderType'] = 'placeholderInput';
                        }
                        break;
                    }
                default:
                    $this->logger->error('wrong TCA type for field ' . $field . ' in table ' . $table);
                    break;
            }
        } else {
            $this->logger->error(
                'no TCA configuration for the given column: ' . $field . ' in table ' . $table
            );
        }
    }
}
