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
     * @var array
     */
    protected $placeholderConfigurationUtility;

    public function __construct()
    {
        $this->placeholderConfigurationUtility =
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
            $this->placeholderConfigurationUtility->getPlaceholderFieldConfiguration() as $table => $configuration
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
            if ($tca[$table]['columns'][$field]['config']['type'] === 'input') {
                if (!is_null($tcaType)) {
                    $tca[$table]['types'][$tcaType]['columnsOverrides'][$field]['config']['type'] = 'user';
                    $tca[$table]['types'][$tcaType]['columnsOverrides'][$field]['config']['renderType'] = 'placeholder';
                } else {
                    $tca[$table]['columns'][$field]['config']['type'] = 'user';
                    $tca[$table]['columns'][$field]['config']['renderType'] = 'placeholder';
                }
            } else {
                $this->logger->error('wrong TCA type for field ' . $field . ' in table ' . $table);
            }
        } else {
            $this->logger->error(
                'no TCA configuration for the given column: ' . $field . ' in table ' . $table
            );
        }
    }
}
