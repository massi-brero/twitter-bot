<?php
/**
 * Created by PhpStorm.
 * User: massi
 * Date: 03.10.18
 * Time: 12:32
 */

namespace TwitterBot\Models;


class TwitterStatistic
{
    private $total = 0;
    private $joyPercentage = 0.0;
    private $angryPercentage = 0.0;
    private $sadPercentage = 0.0;
    private $fearPercentage = 0.0;

    /**
     * @return int
     */
    public function getTotal() : int
    {
        return $this->total;
    }

    /**
     * @param int $total
     * @return TwitterStatistic
     */
    public function setTotal(int $total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return float
     */
    public function getJoyPercentage() : float
    {
        return $this->joyPercentage;
    }

    /**
     * @param mixed $joyPercentage
     * @return TwitterStatistic
     */
    public function setJoyPercentage(float $joyPercentage)
    {
        $this->joyPercentage = $joyPercentage;
        return $this;
    }

    /**
     * @return float
     */
    public function getAngryPercentage() : float
    {
        return $this->angryPercentage;
    }

    /**
     * @param mixed $angryPercentage
     * @return TwitterStatistic
     */
    public function setAngryPercentage(float $angryPercentage)
    {
        $this->angryPercentage = $angryPercentage;
        return $this;
    }

    /**
     * @return float
     */
    public function getSadPercentage() : float
    {
        return $this->sadPercentage;
    }

    /**
     * @param mixed $sadPercentage
     * @return TwitterStatistic
     */
    public function setSadPercentage(float $sadPercentage)
    {
        $this->sadPercentage = $sadPercentage;
        return $this;
    }

    /**
     * @return float
     */
    public function getFearPercentage() : float
    {
        return $this->fearPercentage;
    }

    /**
     * @param mixed $fearPercentage
     * @return TwitterStatistic
     */
    public function setFearPercentage(float $fearPercentage)
    {
        $this->fearPercentage = $fearPercentage;
        return $this;
    }


}