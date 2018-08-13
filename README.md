# OpenWeatherMap PHP Class
PHP class to get current and forecast weather data from OpenWeatherMap
OpenWeatherMap PHP Class can be used to fetch OpenWeatherMap weather data. 
There are two PHP class available in this class. One is for getting the current weather data and the other one is to get the weather forecast dta.
The classes was developed and maintaining by [CodeSpeedy Coding Solutions](https://www.codespeedy.com/). Below is given the usage of the classes:
### Get current weather data
To get the current weather data, you have to include the classes/CurrentClass.php.
After that create the object of the class and pass the OpenWeatherMap API key, city and unit in the time of creating the object just like below:
'''
$currentObj = new CurrentClass("OpenWeatherMap_API_KEY", "CITY", "UNIT");
'''
Currently the supported units are metric and imperial.
Now for example, if you want to show the current temperetature, then below is the code:
'''
echo $currentObj->temp();
'''
