<?php
namespace KoninklijkeCollective\KoningGeo\Domain\Model;

/**
 * Model: Location
 *
 * @package KoninklijkeCollective\KoningGeo\Domain\Model
 */
class Location
{
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
     * @return int
     */
    public function getUidForeign()
    {
        return $this->uidForeign;
    }

    /**
     * @param int $uidForeign
     * @return void
     */
    public function setUidForeign($uidForeign)
    {
        $this->uidForeign = $uidForeign;
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
     * @return void
     */
    public function setTablename($tablename)
    {
        $this->tablename = $tablename;
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
     * @return void
     */
    public function setLocation($location)
    {
        $this->location = $location;
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
     * @return void
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
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
     * @return void
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
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
     * @return void
     */
    public function setViewportNeLatitude($viewportNeLatitude)
    {
        $this->viewportNeLatitude = $viewportNeLatitude;
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
     * @return void
     */
    public function setViewportNeLongitude($viewportNeLongitude)
    {
        $this->viewportNeLongitude = $viewportNeLongitude;
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
     * @return void
     */
    public function setViewportSwLatitude($viewportSwLatitude)
    {
        $this->viewportSwLatitude = $viewportSwLatitude;
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
     * @return void
     */
    public function setViewportSwLongitude($viewportSwLongitude)
    {
        $this->viewportSwLongitude = $viewportSwLongitude;
    }
}
