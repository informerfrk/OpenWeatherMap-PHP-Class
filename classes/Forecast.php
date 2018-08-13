<?php

/**
* 
api.openweathermap.org/data/2.5/forecast/daily?q=London&mode=xml&units=metric&cnt=7
http://api.openweathermap.org/data/2.5/forecast/daily?q=". $city .",". $country ."&units=metric&cnt=4&appid=". $owapi
*/
class Forecast
{
  private $OWAPI, $cityname, $fcArr, $fcUnit;
  public function __construct($api, $city, $fcUnit)
  {
     $this->OWAPI = $api;
     $this->cityname = $city;
     $this->fcUnit = $fcUnit;

     $owUrl = "http://api.openweathermap.org/data/2.5/forecast/daily?q=".$this->cityname."&units=".$this->fcUnit."&cnt=7&appid=".$this->OWAPI."";

     $context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
	   $filecontents = @file_get_contents($owUrl,false,$context);

    if ($filecontents === false) {
      $dummyJson = '{
  "city": {
    "id": 2643743,
    "name": "London",
    "coord": {
      "lon": -0.1258,
      "lat": 51.5085
    },
    "country": "GB",
    "population": 0
  },
  "cod": "200",
  "message": 0.2924782,
  "cnt": 5,
  "list": [
    {
      "dt": 1501070400,
      "temp": {
        "day": 19.36,
        "min": 14.4,
        "max": 19.36,
        "night": 14.4,
        "eve": 18.84,
        "morn": 19.36
      },
      "pressure": 1012.24,
      "humidity": 93,
      "weather": [
        {
          "id": 500,
          "main": "Rain",
          "description": "light rain",
          "icon": "10d"
        }
      ],
      "speed": 5.71,
      "deg": 223,
      "clouds": 100,
      "rain": 2.07
    },
    {
      "dt": 1501156800,
      "temp": {
        "day": 17.7,
        "min": 13.98,
        "max": 18.49,
        "night": 14.85,
        "eve": 17.16,
        "morn": 13.98
      },
      "pressure": 1011.66,
      "humidity": 76,
      "weather": [
        {
          "id": 500,
          "main": "Rain",
          "description": "light rain",
          "icon": "10d"
        }
      ],
      "speed": 5.22,
      "deg": 230,
      "clouds": 80,
      "rain": 0.27
    },
    {
      "dt": 1501243200,
      "temp": {
        "day": 19.21,
        "min": 14.85,
        "max": 19.21,
        "night": 16.95,
        "eve": 18.19,
        "morn": 14.85
      },
      "pressure": 1014.59,
      "humidity": 71,
      "weather": [
        {
          "id": 500,
          "main": "Rain",
          "description": "light rain",
          "icon": "10d"
        }
      ],
      "speed": 6.22,
      "deg": 243,
      "clouds": 76,
      "rain": 0.84
    },
    {
      "dt": 1501329600,
      "temp": {
        "day": 20.18,
        "min": 15.24,
        "max": 21.13,
        "night": 15.24,
        "eve": 19.49,
        "morn": 17.25
      },
      "pressure": 1014.18,
      "humidity": 68,
      "weather": [
        {
          "id": 500,
          "main": "Rain",
          "description": "light rain",
          "icon": "10d"
        }
      ],
      "speed": 4.67,
      "deg": 237,
      "clouds": 36,
      "rain": 0.23
    },
    {
      "dt": 1501416000,
      "temp": {
        "day": 20.11,
        "min": 14.53,
        "max": 20.11,
        "night": 14.53,
        "eve": 20.09,
        "morn": 16.88
      },
      "pressure": 1010.57,
      "humidity": 0,
      "weather": [
        {
          "id": 501,
          "main": "Rain",
          "description": "moderate rain",
          "icon": "10d"
        }
      ],
      "speed": 4.57,
      "deg": 236,
      "clouds": 62,
      "rain": 3.08
    }
  ]
}';
      $this->getArr = json_decode($dummyJson, true);
    }
    else {
      $this->fcArr = json_decode($filecontents, true);
    }
  }
 /* public function fcArr {
      
  }*/

  public function fcdt($num) {
       return $this->fcArr["list"][$num]["dt"];
  }

  public function maxTemp($num) {
       return round($this->fcArr["list"][$num]["temp"]["max"], 1) ;
  }
  public function minTemp($num) {
       return round($this->fcArr["list"][$num]["temp"]["min"], 1) ;
  }

   public function temp_unit()
   {
     if ($this->fcUnit == 'metric') {
        return "°C";
     } else {
        return "°F";
     }
   }

  public function fcPressure($num) {
    return $this->fcArr["list"][$num]["pressure"];
  }
  public function fcHumidity($num) {
    return $this->fcArr["list"][$num]["humidity"];
  }

    public function fcHumiditywithP($num) {
    if ($this->fcArr["list"][$num]["humidity"] == 0) {
      return "N/A";
    } else {
      return $this->fcArr["list"][$num]["humidity"]."%";
    }
    
  }

  public function fcWindSpeed($num) {

    if ($this->fcUnit == 'metric') {
      $wind_speed = $this->fcArr["list"][$num]["speed"];
      $wind_speed_km = $wind_speed/1000;
      $w_speed_per_hour = $wind_speed_km*3600;
      return round($w_speed_per_hour,2);
    } else {
       return round($this->fcArr["list"][$num]["speed"],2);
    }
  }

  public function fcWindSpeedUnit() {
    if ($this->fcUnit == 'metric') {
      return 'km/h';
    } else {
      return 'mph';
    }
  }

  public function fcWindDeg($num) {
    return $this->fcArr["list"][$num]["deg"];
  }
  public function fcClouds($num) {
    return $this->fcArr["list"][$num]["clouds"];
  }

  public function fcRain($num) {
   
    if (@array_key_exists("rain",$this->fcArr["list"][$num])) {

        if ($this->fcUnit == 'metric') {
          return @$this->fcArr["list"][$num]["rain"]."mm";
        } else {
          $fcrain_value = @$this->fcArr["list"][$num]["rain"] * 0.0393700787;
          return @round($fcrain_value,4).' inch';
        }
        
    } elseif (@array_key_exists("snow",$this->fcArr["list"][$num])) {

        if ($this->fcUnit == 'metric') {
          return @$this->fcArr["list"][$num]["snow"]." mm";
        } else {
          $fcrain_value = @$this->fcArr["list"][$num]["snow"] * 0.0393700787;
          return @round($fcrain_value,4).' inch';
        }

    } else {
      return "N/A";
    }
    
  }
  
  public function fcConditionMain($num) {
    return $this->fcArr["list"][$num]["weather"][0]['main'];
  }
  public function fcConditionDes($num) {
    return $this->fcArr["list"][$num]["weather"][0]['description'];
  }
  public function fcIcon($num) {
    return $this->fcArr["list"][$num]["weather"][0]['icon'];
  }
}


?>