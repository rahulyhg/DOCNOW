<?php
/*
Copyright (c) 2010 eBussola.com

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in
 all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 THE SOFTWARE.
*/

/**
 * Calcule two points on a sphere (the Earth) from their latitudes and longitudes.
 *
 * More info: http://www.phpclasses.org/package/6480-PHP-Two-Latitude-and-Longitude-points-calculation.html
 * 
 * @author Leonardo Branco Shinagawa <leonardo@ebussola.com>
 *
 */
class Haversine
{
	
	const KILOMETER = 1;
	const METER = 2;
	const MILE = 3;
	const YARD = 4;
	const FEET = 5;
	const INCH = 6;
	
	const SUFFIX_KILOMETER = 'km';
	const SUFFIX_METER = 'm';
	const SUFFIX_MILE = 'mi';
	const SUFFIX_YARD = 'yd';
	const SUFFIX_FEET = 'ft';
	const SUFFIX_INCH = 'in';
	
	protected $_r = 6371; // Earth's Radius
	protected $point1;
	protected $point2;
	protected $dLat;
	protected $dLon;
	public $showSuffix;
	public $defaultUnit;
	public $decimals = 2;
	public $dec_point = ',';
	public $thousands_sep = '.';
	
	protected $attributes;
	
	public function __get($attr)
	{
		if (isset($this->attributes[$attr]))
			return $this->attributes[$attr];
		else
			$this->attributes[$attr] = $this->{'get'.ucfirst($attr)}();
			
		return $this->attributes[$attr];
	}
	
	public function __set($attr, $value)
	{
		if (!isset($this->attributes[$attr]))
			if (method_exists($this, 'set'.ucfirst($attr)))				
				$this->attributes[$attr] = $this->{'set'.ucfirst($attr)};
			else
				$this->attributes[$attr] = $value;
		else
			throw new Exception('I Can´t change this attribute, It´s already filled');
	}
	
	public function __construct($point1=array(), $point2=array())
	{
		$this->setPoint1($point1);
		$this->setPoint2($point2);
		
		$this->setDefaultUnit(self::KILOMETER);
		$this->showSuffix(True);
	}
	
	public function __toString()
	{
		return $this->getString();
	}
	
	public function setPoint1(array $value)
	{
		$this->point1 = $value;
		$this->attributes = null;
	}
	
	public function setPoint2(array $value)
	{
		$this->point2 = $value;
		$this->attributes = null;
	}
	
	public function showSuffix($flag)
	{
		$this->showSuffix = $flag;
	}
	
	public function setDefaultUnit($unit)
	{
		$this->defaultUnit = $unit;
	}
	
	public function getString()
	{
		switch ($this->defaultUnit)
		{
			case self::KILOMETER : 
				$op = number_format($this->km, $this->decimals, $this->dec_point, $this->thousands_sep);
				return $this->showSuffix ? $op.self::SUFFIX_KILOMETER : $op;
				break;
			case self::METER : 
				$op = number_format($this->m, $this->decimals, $this->dec_point, $this->thousands_sep);
				return $this->showSuffix ? $op.self::SUFFIX_METER : $op;
				break;
			case self::MILE : 
				$op = number_format($this->mi, $this->decimals, $this->dec_point, $this->thousands_sep);
				return $this->showSuffix ? $op.self::SUFFIX_MILE : $op;
				break;
			case self::YARD : 
				$op = number_format($this->yd, $this->decimals, $this->dec_point, $this->thousands_sep);
				return $this->showSuffix ? $op.self::SUFFIX_YARD : $op;
				break;
			case self::FEET : 
				$op = number_format($this->ft, $this->decimals, $this->dec_point, $this->thousands_sep);
				return $this->showSuffix ? $op.self::SUFFIX_FEET : $op;
				break;
			case self::INCH : 
				$op = number_format($this->in, $this->decimals, $this->dec_point, $this->thousands_sep);
				return $this->showSuffix ? $op.self::SUFFIX_INCH : $op;
				break;
		}
	}
	
	public function getKm()
	{
		return $this->calcule();
	}
	
	public function getM()
	{
		return $this->km * 1000;
	}
	
	public function getMi()
	{
		return $this->km * 0.621371192;
	}
	
	public function getYd()
	{
		return $this->km * 1093.6133;
	}
	
	public function getFt()
	{
		return $this->km * 3280.8399;
	}
	
	public function getIn()
	{
		return $this->km * 39370.0787;
	}
	
	protected function toRad($v)
	{
		return $v * M_PI / 180;
	}
	
	protected function funcA()
	{
		return sin($this->dLat / 2) * 
			sin($this->dLat /2) + 
			cos($this->toRad($this->point1['lat'])) *
			cos($this->torad($this->point2['lat'])) *
			sin($this->dLon / 2) *
			sin($this->dLon / 2);
	}
	
	protected function funcB()
	{
		$a = $this->funcA();
		return 2 * atan2(sqrt($a), sqrt(1 - $a));
	}
	
	protected function calcule()
	{
		$this->dLat = $this->toRad($this->point1['lat'] - $this->point2['lat']);
		$this->dLon = $this->toRad($this->point1['lon'] - $this->point2['lon']);
		return $this->_r * $this->funcB();
	}
	
}
