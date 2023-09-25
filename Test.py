from tinkerforge.ip_connection import IPConnection
from tinkerforge.bricklet_air_quality import BrickletAirQuality
from tinkerforge.bricklet_outdoor_weather import BrickletOutdoorWeather
from flask import Flask, render_template
import time
import mariadb
import sys

try:
	conn = mariadb.connect(
            user="web211_3",
            password="F0rscher",
            host="server26.webgo24.de",
            port=3306,
            database="web211_db3")

	conn2 = mariadb.connect(
            user="Wetter",
            password="retteW",
            host="localhost",
            port=3306,
            database="WVS_Wetter")

except mariadb.Error as e:
        print(f"Error connecting to MariaDB Platform: {e}")
        sys.exit(1)

cur = conn.cursor()
cur2 = conn2.cursor()
HOST = "localhost"
PORT = 4223
UID_AQ = "X6Y" # Change XYZ to the UID of your Air Quality Bricklet
UID_OW = "Z1i"

ipcon = IPConnection() # Create IP connection
ipcon.connect(HOST, PORT) # Connect to brickd

aq = BrickletAirQuality(UID_AQ, ipcon) # Create device object
ow = BrickletOutdoorWeather(UID_OW, ipcon) # Create device object

ide = ow.get_station_identifiers()



direction = ["North", "North-North East", "North East", "East-North East", "East", "East-South East", "South East", "South-South East", "South", "South-South West", "South West", "West-South West", "West", "West-North West", "North West", "North-North West"]


iaq_index, iaq_index_accuracy, temperature, humidity, air_pressure = aq.get_all_values()

# Connect to MariaDB Platform

WAITING_TIME = 60 * 15
print(ide)
while True:
    #time.sleep(WAITING_TIME)
    time.sleep(1)
    data = ow.get_station_data(ide[0])
    if data[5] == 255:
        sys.exit(1)
    data_dict = {
        "iaq_index_accuracy": iaq_index_accuracy, #
        "iaq_index": iaq_index,
        "temperature": temperature, # 100 °C   
        "humidity": humidity,  # 100°C     
        "air_pressure": air_pressure, # 100hpa        7
        "r_temperature": data[0],   # 10 °C           1
        "r_humidity": data[1],      # %               2
        "r_wind_speed": data[2]*10,    # 1/10 m/s     3
        "r_gust_of_wind": data[3]*10,  # 1/10 m/s     4
        "rainfall": data[4]*10,        # 1/10 mm      5
        "wind_dir": direction[data[5]],        #      6
        "batterywarning": data[6],
        "lastchange": data[-1]
    }
    sqlquerry = "INSERT INTO Data (temperature, humidity, wind_speed, gust_of_wind, rainfall, direction, air_pressure) VALUES (?, ?, ?, ?, ?, ?, ?)"
    cur.execute(sqlquerry, (data_dict["r_temperature"], data_dict["r_humidity"], data_dict["r_wind_speed"], data_dict["r_gust_of_wind"], data_dict["rainfall"], data_dict["wind_dir"], data_dict["air_pressure"]))
    cur2.execute(sqlquerry,(data_dict["r_temperature"], data_dict["r_humidity"], data_dict["r_wind_speed"], data_dict["r_gust_of_wind"], data_dict["rainfall"], data_dict["wind_dir"], data_dict["air_pressure"]))
    conn.commit()
    conn2.commit()

    
    
#app = Flask(__name__)
#
#@app.route("/")
#def members():
#    data = ow.get_station_data(ide[0])
#    data2 = {"iaq_index_accuracy": iaq_index_accuracy, 
#    "temperature": temperature/100,
#    "humidity": humidity/100,
#    "air_pressure": air_pressure/100,
#    "r_temperature": data[0]*10,   # 1/10 °C
#    "r_humidity": data[1],      # %
#    "r_wind_speed": data[2]*10,    # 1/10 m/s
#    "r_gust_of_wind": data[3]*10,  # 1/10 m/s
#    "rainfall": data[4]*10,        # 1/10 mm
#    "wind_dir": direction[data[5]],        #
#    "batterywarning": data[6],
#    "lastchange": data[-1]
#                                                                                                                         }
#    for i, a in data2.items():
#        print(i, a, type(a))
#    return render_template('index2.html', data=data2)
#@app.route("/api")
#def api():
#    iaq_index, iaq_index_accuracy, temperature, humidity, air_pressure = aq.get_all_values() 
#    print("IAQ Index: " + str(iaq_index))
#    if iaq_index_accuracy == aq.ACCURACY_UNRELIABLE:
#        print("IAQ Index Accuracy: Unreliable")
#    elif iaq_index_accuracy == aq.ACCURACY_LOW:
#        print("IAQ Index Accuracy: Low")
#    elif iaq_index_accuracy == aq.ACCURACY_MEDIUM:
#        print("IAQ Index Accuracy: Medium")
#    elif iaq_index_accuracy == aq.ACCURACY_HIGH:
#        print("IAQ Index Accuracy: High")
#    data = ow.get_station_data(ide[0])
#    if data[5] == 255:
#        return "error"
#    return {
#        "iaq_index_accuracy": iaq_index_accuracy, #
#        "iaq_index": iaq_index,
#        "temperature": temperature/100,
#        "humidity": humidity/100,
#        "air_pressure": air_pressure,
#        "r_temperature": data[0]*10,   # 1/10 °C
#        "r_humidity": data[1],      # % 
#        "r_wind_speed": data[2]*10,    # 1/10 m/s
#        "r_gust_of_wind": data[3]*10,  # 1/10 m/s
#        "rainfall": data[4]*10,        # 1/10 mm
#        "wind_dir": direction[data[5]],        # 
#        "batterywarning": data[6],
#        "lastchange": data[-1]
#    }
#if __name__ == "__main__":
#    app.run(host="0.0.0.0", port=80)
#
