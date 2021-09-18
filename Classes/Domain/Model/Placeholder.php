<?php

declare(strict_types=1);

namespace SebastianStein\Placeholder\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Placeholder extends AbstractEntity
{
    const TABLE = 'tx_placeholder_domain_model_placeholder';

    protected $markerIdentifier = '';
    protected $description = '';
    protected $value = '';

    /**
     * @return string
     */
    public function getMarkerIdentifier(): string
    {
        return $this->markerIdentifier;
    }

    /**
     * @param string $markerIdentifier
     * @return Placeholder
     */
    public function setMarkerIdentifier(string $markerIdentifier): Placeholder
    {
        $this->markerIdentifier = $markerIdentifier;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Placeholder
     */
    public function setDescription(string $description): Placeholder
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Placeholder
     */
    public function setValue(string $value): Placeholder
    {
        $this->value = $value;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'uid' => self::getUid(),
            'markerIdentifier' => self::getMarkerIdentifier(),
            'description' => self::getDescription(),
            'value' => self::getValue()
        ];
    }
}
