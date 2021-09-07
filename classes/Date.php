<?php
class Date
{
    public $year = 0;
    public $month = 0;
    public $day = 0;

    public $dateformat = "Y-m-d"; //2021-09-02
    public $timezone = "asia/ho_chi_minh";

    public function __construct($year = 0, $month = 0, $day = 0)
    {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }
    public static function setTimeZone($timezone)
    {
        date_default_timezone_set($timezone);
    }
    public static function today()
    {
        $today = new Date();
        $today->year = date("Y");
        $today->month = date("m");
        $today->day = date("d");
        return $today;
    }
    public function setToday()
    {
        $this->year = date("Y");
        $this->month = date("m");
        $this->day = date("d");
    }

    public function add($numberOfDay)
    {
        // Tăng thêm số ngày numberOfDay
        $date = date_create($this->year . "-" . $this->month . "-" . $this->day);
        date_add($date, date_interval_create_from_date_string($numberOfDay . " days"));
        $d = new Date();
        $d->year = $date->format("Y");
        $d->month = $date->format("m");
        $d->day = $date->format("d");
        return $d;
    }
    public function sub($numberOfDay)
    {
        // Giảm bớt số ngày numberOfDay
        $date = date_create($this->year . "-" . $this->month . "-" . $this->day);
        date_sub($date, date_interval_create_from_date_string($numberOfDay . " days"));
        $d = new Date();
        $d->year = $date->format("Y");
        $d->month = $date->format("m");
        $d->day = $date->format("d");
        return $d;
    }

    public function setDateFormat($dateformat)
    {
        $this->dateformat = $dateformat;
    }
    public function toString()
    {
        $date = date_create($this->year . "-" . $this->month . "-" . $this->day);
        $string = date_format($date, $this->dateformat);
        return $string;
    }
    public static function getCurrentYear()
    {
        return date("Y"); //2021
    }
    public static function getCurrentMonth()
    {
        return date("m"); //9
    }
    public static function getCurrentDay()
    {
        return date("d"); //2
    }
}
