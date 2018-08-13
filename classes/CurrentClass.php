<?php

class CurrentClass
{
  public $OWAPI, $cityname, $getArr, $unit;
  
  public function __construct($api, $city, $unit)
  {
     $this->OWAPI = $api;
     $this->cityname = $city;
     $this->unit = $unit;
     $owUrl = "http://api.openweathermap.org/data/2.5/weather?q=".$this->cityname."&units=".$this->unit."&appid=".$this->OWAPI."";

     $context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
     $getJson = @file_get_contents($owUrl,false,$context);

    if ($getJson === false) {
      $dummyJson = '{
  "coord": {
    "lon": -0.13,
    "lat": 51.51
  },
  "weather": [
    {
      "id": 300,
      "main": "Drizzle",
      "description": "light intensity drizzle",
      "icon": "09d"
    }
  ],
  "base": "stations",
  "main": {
    "temp": 280.32,
    "pressure": 1012,
    "humidity": 81,
    "temp_min": 279.15,
    "temp_max": 281.15
  },
  "visibility": 10000,
  "wind": {
    "speed": 4.1,
    "deg": 80
  },
  "clouds": {
    "all": 90
  },
  "dt": 1485789600,
  "sys": {
    "type": 1,
    "id": 5091,
    "message": 0.0103,
    "country": "0",
    "sunrise": 1485762037,
    "sunset": 1485794875
  },
  "id": 2643743,
  "name": "Location not found",
  "cod": 200
}';
      $this->getArr = json_decode($dummyJson, true);
    }
    else {
     $this->getArr = json_decode($getJson, true);
    }
  }
   
   /*public function getArr {
     
   }*/
  // get lattitude
   public function getLat() {
    return $this->getArr['coord']['lat'];
   }
   // Get longitude
   public function getLon() {
     if (array_key_exists('lon', $this->getArr['coord'])) {
      return $this->getArr['coord']['lon'];
     } else {return "not available";}
   }
   // Current temperature
   
   public function temp() {
      if (array_key_exists('temp', $this->getArr['main'])) {
      return $this->getArr['main']['temp'];
      }
   }
   // Pressure
   public function pressure() {
      if (array_key_exists('pressure', $this->getArr['main'])) {
      return $this->getArr['main']['pressure'];
      }
   }

   // Humidity
   public function humidity() {
      if (array_key_exists('humidity', $this->getArr['main'])) {
      return $this->getArr['main']['humidity'];
      }
   }
   // condition main
    public function conditionMain() {
      if (array_key_exists('main', $this->getArr['weather'][0])) {
      return $this->getArr['weather'][0]['main'];
      } else {return $this->getArr['weather'][0]['description'];}
   }
   //condition desc
   public function conditionDes() {
     if (array_key_exists('description', $this->getArr['weather'][0])) {
     return $this->getArr['weather'][0]['description'];
      } else {return $this->getArr['weather'][0]['main'];}
   }

   //icon
   public function icon() {
      if (array_key_exists('icon', $this->getArr['weather'][0])) {
      return $this->getArr['weather'][0]['icon'];
      }
   }

   //base
   public function base() {
      if (array_key_exists('base', $this->getArr)) {
        return $this->getArr['base'];
      }
   }

   //visibility
   public function visibility() {
      if (array_key_exists('visibility', $this->getArr)) {
         $visibility_km = $this->getArr['visibility']/1000;
         return round($visibility_km,2). " km";
      } else {return "Not available";}
   }

   //wind speed
   public function windSpeed() {
      if (array_key_exists('speed', $this->getArr['wind'])) {
        if ($this->unit == 'metric') {
          
           $w_speed_km = $this->getArr['wind']['speed']/1000;
           $w_speed_per_hour = $w_speed_km*3600;

        } else {
           $w_speed_per_hour = $this->getArr['wind']['speed'];
        }
      return $w_speed_per_hour;
      }  else {return "Not available";}
   }

   // wind deg
   public function windDeg() {
      if (array_key_exists('deg', $this->getArr['wind'])) {
      return $this->getArr['wind']['deg'];
      } else {return "Not available";}
   }

   //clouds all
   public function clouds() {
      if (array_key_exists('all', $this->getArr['clouds'])) {
      return $this->getArr['clouds']['all'];
      }
   }

   //dt
   public function dt() {
    $timestamp = $this->getArr['dt'];
    $formattedDT = gmdate("Y-m-d\TH:i:s\Z", $timestamp);
    return $formattedDT;
   }

   //sys type
   public function systype() {
      if (array_key_exists('type', $this->getArr['sys'])) {
      return $this->getArr['sys']['type'];
      }
   }

   //id
   public function sysid() {
      if (array_key_exists('id', $this->getArr['sys'])) {
      return $this->getArr['sys']['id'];
      }
   }

   //message
   public function sysmessage() {
      if (array_key_exists('message', $this->getArr['sys'])) {
      return $this->getArr['sys']['message'];
      }
   }

   //country
   public function getCountry() {
      if (array_key_exists('country', $this->getArr['sys'])) {
      return $this->getArr['sys']['country'];
      }
   }

   // sunrise
   public function sunrise() {
      if (array_key_exists('sunrise', $this->getArr['sys'])) {
      return $this->getArr['sys']['sunrise'];
      }
   }

   //sunset
   public function sunset() {
      if (array_key_exists('sunset', $this->getArr['sys'])) {
        return $this->getArr['sys']['sunset'];
     }
   }
   
   public function getCity() {
    if (array_key_exists('name', $this->getArr)) {
      return $this->getArr['name'];
   }
   }

   public function wind_speed_unit()
   {
     if ($this->unit == 'metric') {
        return "km/hour";
     } else {
        return "miles/hour";
     }
   }


   public function temp_unit()
   {
     if ($this->unit == 'metric') {
        return "°C";
     } else {
        return "°F";
     }
   }


   public function feels_like_temp()
   {


           if ($this->unit == 'metric') {
              $ftemp = $this->temp() * 1.8 + 32;

            if ($ftemp < 80) {
              $FeelsLike = $ftemp;
              $FeelsLike = ($FeelsLike - 32)*5/9;
            } else {

                $FeelsLike = -42.379 + 2.04901523*$ftemp + 10.14333127*$this->humidity() - .22475541*$ftemp*$this->humidity() - .00683783*$ftemp*$ftemp - .05481717*$this->humidity()*$this->humidity() + .00122874*$ftemp*$ftemp*$this->humidity() + .00085282*$ftemp*$this->humidity()*$this->humidity() - .00000199*$ftemp*$ftemp*$this->humidity()*$this->humidity();
                //echo $FeelsLike;
                $FeelsLike = ($FeelsLike - 32)*5/9;
            }

     } else {
        


      $ftemp = $this->temp();

          
      if ($ftemp < 80) {
        $FeelsLike = $ftemp;
      } else {
        //echo $ftemp;
          $FeelsLike = -42.379 + 2.04901523*$ftemp + 10.14333127*$this->humidity() - .22475541*$ftemp*$this->humidity() - .00683783*$ftemp*$ftemp - .05481717*$this->humidity()*$this->humidity() + .00122874*$ftemp*$ftemp*$this->humidity() + .00085282*$ftemp*$this->humidity()*$this->humidity() - .00000199*$ftemp*$ftemp*$this->humidity()*$this->humidity();
          //$FeelsLike = ($FeelsLike - 32)*5/9;
      }

     }

        return round($FeelsLike,1);
   }

}

?>
