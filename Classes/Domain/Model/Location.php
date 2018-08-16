<?php

namespace KoninklijkeCollective\KoningGeo\Domain\Model;

/**
 * Model: Location
 *
 * @package KoninklijkeCollective\KoningGeo\Domain\Model
 */
class Location
{
    const TABLE = 'tx_koninggeo_domain_model_location';

    /**
     * @var int
     */
    protected $uidForeign;

    /**
     * @var string
     */
    protected $tablename;

    /**
     * @var string
     */
    protected $location;

    /**
     * @var float
     */
    protected $latitude;

    /**
     * @var float
     */
    protected $longitude;

    /**
     * @var float
     */
    protected $viewportNeLatitude;

    /**
     * @var float
     */
    protected $viewportNeLongitude;

    /**
     * @var float
     */
    protected $viewportSwLatitude;

    /**
     * @var float
     */
    protected $viewportSwLongitude;

    /**
     * @param array $row
     * @return static
     */
    public static function create(array $row = []): Location
    {
        $object = new static();
        foreach ($row as $column => $value) {
            $methodName = 'set' . \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($column);
            if (method_exists($object, $methodName)) {
                $object->{$methodName}($value);
            }
        }
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
            'location' => $this->getLocation(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
            'viewport_ne_latitude' => $this->getViewportNeLatitude(),
            'viewport_ne_longitude' => $this->getViewportNeLongitude(),
            'viewport_sw_latitude' => $this->getViewportSwLatitude(),
            'viewport_sw_longitude' => $this->getViewportSwLongitude(),
        ];
    }

    /**
     * @return int
     */
    public function getUidForeign()
    {
        return $this->uidForeign;
    }

    /**
     * @param int $uidForeign
     * @return Location
     */
    public function setUidForeign($uidForeign)
    {
        $this->uidForeign = $uidForeign;
        return $this;
    }

    /**
     * @return string
     */
    public function getTablename()
    {
        return $this->tablename;
    }

    /**
     * @param string $tablename
     * @return Location
     */
    public function setTablename($tablename)
    {
        $this->tablename = $tablename;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return Location
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return Location
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return Location
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getViewportNeLatitude()
    {
        return $this->viewportNeLatitude;
    }

    /**
     * @param float $viewportNeLatitude
     * @return Location
     */
    public function setViewportNeLatitude($viewportNeLatitude)
    {
        $this->viewportNeLatitude = $viewportNeLatitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getViewportNeLongitude()
    {
        return $this->viewportNeLongitude;
    }

    /**
     * @param float $viewportNeLongitude
     * @return Location
     */
    public function setViewportNeLongitude($viewportNeLongitude)
    {
        $this->viewportNeLongitude = $viewportNeLongitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getViewportSwLatitude()
    {
        return $this->viewportSwLatitude;
    }

    /**
     * @param float $viewportSwLatitude
     * @return Location
     */
    public function setViewportSwLatitude($viewportSwLatitude)
    {
        $this->viewportSwLatitude = $viewportSwLatitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getViewportSwLongitude()
    {
        return $this->viewportSwLongitude;
    }

    /**
     * @param float $viewportSwLongitude
     * @return Location
     */
    public function setViewportSwLongitude($viewportSwLongitude)
    {
        $this->viewportSwLongitude = $viewportSwLongitude;
        return $this;
    }
}
