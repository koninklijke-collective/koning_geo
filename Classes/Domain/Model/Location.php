<?php

namespace KoninklijkeCollective\KoningGeo\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Model: Location
 */
class Location
{
    public const TABLE = 'tx_koninggeo_domain_model_location';

    /** @var int */
    protected $uidForeign;

    /** @var string */
    protected $tablename;

    /** @var string */
    protected $placeId;

    /** @var string */
    protected $label;

    /** @var float */
    protected $latitude;

    /** @var float */
    protected $longitude;

    /** @var string */
    protected $response;

    /**
     * @param  array  $row
     * @return \KoninklijkeCollective\KoningGeo\Domain\Model\Location
     */
    public static function create(array $row = []): Location
    {
        $object = new static();
        foreach ($row as $column => $value) {
            $methodName = 'set' . GeneralUtility::underscoredToUpperCamelCase($column);
            if (method_exists($object, $methodName)) {
                $object->{$methodName}($value);
            }
        }

        return $object;
    }

    /**
     * @param  int  $id
     * @param  string  $table
     * @param  array  $response
     * @return \KoninklijkeCollective\KoningGeo\Domain\Model\Location
     */
    public static function createForResponse(int $id, string $table, array $response): Location
    {
        $object = new static();
        $object
            ->setUidForeign($id)
            ->setTablename($table)
            ->setPlaceId($response['place_id'] ?? '')
            ->setLabel($response['formatted_address'] ?? '')
            ->setLatitude($response['geometry']['location']['lat'] ?? 0.0)
            ->setLongitude($response['geometry']['location']['lng'] ?? 0.0)
            ->setResponse(json_encode($response) ?? '');

        return $object;
    }

    /**
     * Return database values for insert
     *
     * @return array
     */
    public function values(): array
    {
        return [
            'uid_foreign' => $this->getUidForeign(),
            'tablename' => $this->getTablename(),
            'place_id' => $this->getPlaceId(),
            'label' => $this->getLabel(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
            'response' => $this->getResponse(),
        ];
    }

    /**
     * @return int
     */
    public function getUidForeign(): ?int
    {
        return $this->uidForeign;
    }

    /**
     * @param  int  $uidForeign
     * @return \KoninklijkeCollective\KoningGeo\Domain\Model\Location
     */
    public function setUidForeign(int $uidForeign): Location
    {
        $this->uidForeign = $uidForeign;

        return $this;
    }

    /**
     * @return string
     */
    public function getTablename(): ?string
    {
        return $this->tablename;
    }

    /**
     * @param  string  $tablename
     * @return \KoninklijkeCollective\KoningGeo\Domain\Model\Location
     */
    public function setTablename(string $tablename): Location
    {
        $this->tablename = $tablename;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlaceId(): ?string
    {
        return $this->placeId;
    }

    /**
     * @param  string  $placeId
     * @return $this
     */
    public function setPlaceId(string $placeId): Location
    {
        $this->placeId = $placeId;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param  string  $label
     * @return \KoninklijkeCollective\KoningGeo\Domain\Model\Location
     */
    public function setLabel(string $label): Location
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param  float  $latitude
     * @return \KoninklijkeCollective\KoningGeo\Domain\Model\Location
     */
    public function setLatitude(float $latitude): Location
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @param  float  $longitude
     * @return \KoninklijkeCollective\KoningGeo\Domain\Model\Location
     */
    public function setLongitude(float $longitude): Location
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return string
     */
    public function getResponse(): ?string
    {
        return $this->response;
    }

    /**
     * @param  string  $response
     * @return \KoninklijkeCollective\KoningGeo\Domain\Model\Location
     */
    public function setResponse(string $response): Location
    {
        $this->response = $response;

        return $this;
    }
}
