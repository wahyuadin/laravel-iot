#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>
#include <WiFiClientSecureBearSSL.h>
const char* host = "api-ghina.000webhostapp.com";

//#ifndef STASSID
//#define STASSID "ASTAGFIRULLOH"
//#define STAPSK "1a2b3c4d5e6f"
//#endif

#ifndef STASSID
#define STASSID "BOT.ru"
#define STAPSK "aabbccdd"
#endif

#include <Adafruit_Sensor.h>
#include <DHT.h>
#include <DHT_U.h>

#define DHTTYPE DHT11     // DHT 11

const int DHTPIN = D5;
const int ledPin_hijau = D8; 
const int ledPin_kuning = D7; 
const int ledPin_merah = D6; 
const int relay = D0;
const int sensor_pin = A0;

float suhu_moisture;

DHT_Unified dht(DHTPIN, DHTTYPE);
uint32_t delayMS;

float kering = 0.0;
float normal = 0.0;
float basah = 0.0;

void fuzzifikasi(){
  // Kelembapan
  if (suhu_moisture <= 10.00) {
    kering = 1.0;
  } else if (suhu_moisture > 10.00 && suhu_moisture <= 20.00) {
    kering = (20.00 - suhu_moisture) / 10.00;
  } else {
    kering = 0.0;
  }

  if (suhu_moisture >= 20.00 && suhu_moisture <= 30.00) {
    normal = (suhu_moisture - 20.00) / 10.00;
  } else if (suhu_moisture > 10.00 && suhu_moisture <= 20.00) {
    normal = (suhu_moisture - 10.00) / 10.00;
  } else {
    normal = 0.0;
  }

  if (suhu_moisture >= 40.00) {
    basah = 1.0;
  } else if (suhu_moisture > 30.00 && suhu_moisture <= 40.00) {
    basah = (suhu_moisture - 30.00) / 10.00;
  } else {
    basah = 0.0;
  }
}

DynamicJsonDocument jsonDocument(500);

void setup() {
  delay(500);
  Serial.begin(115200);
  dht.begin();
  sensor_t sensor;
  dht.temperature().getSensor(&sensor);
  dht.humidity().getSensor(&sensor);
  delayMS = sensor.min_delay / 1000;

  pinMode(ledPin_hijau, OUTPUT);
  pinMode(ledPin_kuning, OUTPUT);
  pinMode(ledPin_merah, OUTPUT);
  pinMode(relay, OUTPUT);
  
  WiFi.begin(STASSID, STAPSK);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
    digitalWrite(ledPin_hijau, LOW);
    digitalWrite(ledPin_kuning, LOW);
    digitalWrite(ledPin_merah, LOW);
    digitalWrite(relay, LOW);
    
  }

  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
  
  
}

void loop() {
  suhu_moisture = (100 - ((analogRead(sensor_pin) / 1024.0) * 100));
  delay(delayMS);
  sensors_event_t event;
  dht.temperature().getEvent(&event);
  digitalWrite (relay, LOW); 
  fuzzifikasi(); 
  

  if (isnan(event.temperature) || isnan(event.relative_humidity)) {
    Serial.println(F("Error reading temperature or humidity!"));
  } else {
    Serial.print(F("Temperature: "));
    Serial.print(event.temperature);
    Serial.println(F("°C"));

    Serial.print(F("Humidity: "));
    Serial.print(event.relative_humidity);
    Serial.println(F("%"));
    Serial.println("Kelembapan :" + String(suhu_moisture) + "%"); 
    String status;
        if (suhu_moisture >= 40.00) {
          status = "basah";
        } else if (suhu_moisture >= 10.00 && suhu_moisture <= 20.00) {
          status = "normal";
        } else if (suhu_moisture > 20.00 && suhu_moisture <= 30.00) {
          status = "normal";
        } else if (suhu_moisture >= 0.00 && suhu_moisture <= 10.00) {
          status = "kering";
        } else {
          status = "kekeringan";
        }
    if (WiFi.status() == WL_CONNECTED) {
      std::unique_ptr<BearSSL::WiFiClientSecure> client(new BearSSL::WiFiClientSecure);
      client->setInsecure();
      HTTPClient https;
      Serial.print("[HTTPS] begin...\n");
      int httpCode = 0;

      if (https.begin(*client, host, 443,"/api/api.php/")) {  // HTTPS
        Serial.print("[HTTPS] POST...\n");
//        https.addHeader("Content-Type", "application/json");
        https.addHeader("Authorization", "Bearer 3c9d6f412019a23d7be9dd7ada99bba623fa05e84be22151941808411fcd");
        jsonDocument["kelembapan"] = String(suhu_moisture) + "%";
        jsonDocument["humidity"] = String(event.relative_humidity) + "%";
        jsonDocument["temperature"] = String(event.temperature) + "°C";
        jsonDocument["status"] = status;
        jsonDocument["value"] = "";

        if (suhu_moisture >= 40.00) {
          jsonDocument["value"] = String(basah);
        } else if (suhu_moisture >= 10.00 && suhu_moisture <= 20.00) {
          jsonDocument["value"] = String(normal);
        } else if (suhu_moisture > 20.00 && suhu_moisture <= 30.00) {
          jsonDocument["value"] = String(normal);
        } else if (suhu_moisture >= 0.00 && suhu_moisture <= 10.00) {
          jsonDocument["value"] = String(kering);
        } else {
          jsonDocument["value"] = "0.00";
        }

        String jsonString;
        serializeJson(jsonDocument, jsonString);
        Serial.println(jsonString);
        
        httpCode = https.POST(jsonString);
        if (httpCode > 0) {
          Serial.printf("[HTTPS] POST... code: %d\n", httpCode);
          String payload = https.getString();
          Serial.println("Received Payload: " + payload);
          if (httpCode == HTTP_CODE_OK || httpCode == HTTP_CODE_MOVED_PERMANENTLY) {
            if (payload.startsWith("{\"status\":\"success\"")) {
                Serial.println("esp8266/Arduino JSON successful!"); 
            } else {
              Serial.println("esp8266/Arduino JSON has failed");
            }            
//            Serial.println("Response PAYLOAD:" + payload );
            
          }
        } else {
          Serial.printf("[HTTPS] POST... failed, error: %s\n", https.errorToString(httpCode).c_str());
        }
          if (httpCode == 200) {
            if (suhu_moisture >= 40.00) {
              Serial.println("Notifikasi : Media Masih Basah");
              Serial.print("Basah = " + String(suhu_moisture) + "%");
              Serial.println("Fuzzy = " + String(basah));
              digitalWrite(ledPin_hijau, HIGH);
              digitalWrite(ledPin_kuning, LOW);
              digitalWrite(ledPin_merah, LOW);
              digitalWrite(relay, LOW);
            } 
            if (suhu_moisture >= 10.00 && suhu_moisture <= 20.00) {
              Serial.println("Notifikasi : Kelembaban Tanah Masih Cukup");
              Serial.print("Normal = " + String(suhu_moisture) + "%");
              Serial.println("Fuzzy = " + String(normal));
              digitalWrite(ledPin_hijau, LOW);
              digitalWrite(ledPin_kuning, HIGH);
              digitalWrite(ledPin_merah, LOW);
              digitalWrite(relay, HIGH);
            } 
            if (suhu_moisture >= 20.00 && suhu_moisture <= 30.00) {
              Serial.println("Notifikasi : Kelembaban Tanah Masih Cukup");
              Serial.print("Kering = " + String(suhu_moisture) + "%");
              Serial.println("Fuzzy = " + String(normal));
              digitalWrite(ledPin_hijau, LOW);
              digitalWrite(ledPin_kuning, HIGH);
              digitalWrite(ledPin_merah, LOW);
              digitalWrite(relay, HIGH);
            } 
            if (suhu_moisture >= 0.00 && suhu_moisture <= 10.00) {
              Serial.println("Notifikasi : Kelembaban Tanah Masih Cukup");
              Serial.print("Kering = " + String(suhu_moisture) + "%");
              Serial.println("Fuzzy = " + String(kering));
              digitalWrite(ledPin_hijau, LOW);
              digitalWrite(ledPin_kuning, HIGH);
              digitalWrite(ledPin_merah, LOW);
              digitalWrite(relay, HIGH);
            } 
            if (suhu_moisture < 0.00) {
              Serial.println("Notifikasi : Perlu Tambahan Air");
              digitalWrite(ledPin_hijau, LOW);
              digitalWrite(ledPin_kuning, LOW);
              digitalWrite(ledPin_merah, HIGH);
              digitalWrite(relay, LOW);
            }
          } else {
            String response = https.getString();
            Serial.println("Server response: " + response);
          }
          Serial.print("HTTP Response code: ");
//          digitalWrite (relay, LOW); 
          Serial.println(httpCode);
          https.end();
      }
    } else {
      Serial.println("WiFi Disconnected");
      digitalWrite (relay, LOW); 
    }
  }
  delay(5000);
//  delay(10000);

}
