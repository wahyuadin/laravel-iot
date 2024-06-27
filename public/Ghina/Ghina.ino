#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>
#include <WiFiClientSecureBearSSL.h>
#include "DHT.h"
#include <Fuzzy.h>

#ifndef STASSID
#define STASSID "KAMAR"
#define STAPSK "aabbccdd"
#endif

const char* host = "api-ghina.000webhostapp.com";

#define DHTPIN D5 // Pin DHT11
#define DHTTYPE DHT11 // Tipe DHT 11
DHT dht(DHTPIN, DHTTYPE);

const int sensor_pin = A0; // Pin sensor kelembaban tanah
const int ledPin_hijau = D8; 
const int ledPin_kuning = D7; 
const int ledPin_merah = D6; 
const int relay = D0;

// Deklarasi objek Fuzzy
Fuzzy* fuzzy = new Fuzzy();

// Deklarasi himpunan fuzzy untuk suhu
FuzzySet* tempDingin = new FuzzySet(0, 0, 10, 19);
FuzzySet* tempNormal = new FuzzySet(18, 20, 30, 32);
FuzzySet* tempPanas = new FuzzySet(31, 32, 100, 100);

// Deklarasi himpunan fuzzy untuk kelembaban tanah
FuzzySet* soilKering = new FuzzySet(0, 0, 45, 49);
FuzzySet* soilLembab = new FuzzySet(48, 50, 70, 72);
FuzzySet* soilBasah = new FuzzySet(71, 72, 100, 300);

// Deklarasi himpunan fuzzy untuk keputusan penyiraman
FuzzySet* tidakSiram = new FuzzySet(0, 0, 0, 50);
FuzzySet* siram = new FuzzySet(50, 51, 100, 100);

DynamicJsonDocument jsonDocument(500);

void setup() {
  Serial.begin(9600);
  dht.begin();
  WiFi.begin(STASSID, STAPSK);
  
  while (WiFi.status() != WL_CONNECTED) {
    digitalWrite(relay, LOW);
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  // Inisialisasi input fuzzy untuk suhu
  FuzzyInput* temperature = new FuzzyInput(1);
  temperature->addFuzzySet(tempDingin);
  temperature->addFuzzySet(tempNormal);
  temperature->addFuzzySet(tempPanas);
  fuzzy->addFuzzyInput(temperature);

  // Inisialisasi input fuzzy untuk kelembaban tanah
  FuzzyInput* soilMoisture = new FuzzyInput(2);
  soilMoisture->addFuzzySet(soilKering);
  soilMoisture->addFuzzySet(soilLembab);
  soilMoisture->addFuzzySet(soilBasah);
  fuzzy->addFuzzyInput(soilMoisture);

  // Inisialisasi output fuzzy untuk keputusan penyiraman
  FuzzyOutput* waterDecision = new FuzzyOutput(1);
  waterDecision->addFuzzySet(tidakSiram);
  waterDecision->addFuzzySet(siram);
  fuzzy->addFuzzyOutput(waterDecision);

  // Definisi aturan fuzzy
  FuzzyRuleAntecedent* ifPanasAndKering = new FuzzyRuleAntecedent();
  ifPanasAndKering->joinWithAND(tempPanas, soilKering);
  FuzzyRuleConsequent* thenSiram = new FuzzyRuleConsequent();
  thenSiram->addOutput(siram);
  FuzzyRule* fuzzyRule1 = new FuzzyRule(1, ifPanasAndKering, thenSiram);
  fuzzy->addFuzzyRule(fuzzyRule1);

  FuzzyRuleAntecedent* ifNormalAndKering = new FuzzyRuleAntecedent();
  ifNormalAndKering->joinWithAND(tempNormal, soilKering);
  FuzzyRule* fuzzyRule2 = new FuzzyRule(2, ifNormalAndKering, thenSiram);
  fuzzy->addFuzzyRule(fuzzyRule2);

  FuzzyRuleAntecedent* ifDinginAndKering = new FuzzyRuleAntecedent();
  ifDinginAndKering->joinWithAND(tempDingin, soilKering);
  FuzzyRule* fuzzyRule3 = new FuzzyRule(3, ifDinginAndKering, thenSiram);
  fuzzy->addFuzzyRule(fuzzyRule3);

  FuzzyRuleAntecedent* ifPanasAndLembab = new FuzzyRuleAntecedent();
  ifPanasAndLembab->joinWithAND(tempPanas, soilLembab);
  FuzzyRuleConsequent* thenTidakSiram = new FuzzyRuleConsequent();
  thenTidakSiram->addOutput(tidakSiram);
  FuzzyRule* fuzzyRule4 = new FuzzyRule(4, ifPanasAndLembab, thenTidakSiram);
  fuzzy->addFuzzyRule(fuzzyRule4);

  FuzzyRuleAntecedent* ifNormalAndLembab = new FuzzyRuleAntecedent();
  ifNormalAndLembab->joinWithAND(tempNormal, soilLembab);
  FuzzyRule* fuzzyRule5 = new FuzzyRule(5, ifNormalAndLembab, thenTidakSiram);
  fuzzy->addFuzzyRule(fuzzyRule5);

  FuzzyRuleAntecedent* ifDinginAndLembab = new FuzzyRuleAntecedent();
  ifDinginAndLembab->joinWithAND(tempDingin, soilLembab);
  FuzzyRule* fuzzyRule6 = new FuzzyRule(6, ifDinginAndLembab, thenTidakSiram);
  fuzzy->addFuzzyRule(fuzzyRule6);

  FuzzyRuleAntecedent* ifPanasAndBasah = new FuzzyRuleAntecedent();
  ifPanasAndBasah->joinWithAND(tempPanas, soilBasah);
  FuzzyRule* fuzzyRule7 = new FuzzyRule(7, ifPanasAndBasah, thenTidakSiram);
  fuzzy->addFuzzyRule(fuzzyRule7);

  FuzzyRuleAntecedent* ifNormalAndBasah = new FuzzyRuleAntecedent();
  ifNormalAndBasah->joinWithAND(tempNormal, soilBasah);
  FuzzyRule* fuzzyRule8 = new FuzzyRule(8, ifNormalAndBasah, thenTidakSiram);
  fuzzy->addFuzzyRule(fuzzyRule8);

  FuzzyRuleAntecedent* ifDinginAndBasah = new FuzzyRuleAntecedent();
  ifDinginAndBasah->joinWithAND(tempDingin, soilBasah);
  FuzzyRule* fuzzyRule9 = new FuzzyRule(9, ifDinginAndBasah, thenTidakSiram);
  fuzzy->addFuzzyRule(fuzzyRule9);

  // Inisialisasi pin
  pinMode(relay, OUTPUT);
  pinMode(ledPin_hijau, OUTPUT);
  pinMode(ledPin_kuning, OUTPUT);
  pinMode(ledPin_merah, OUTPUT);
}

void loop() {
  // Membaca sensor
  float temperatureValue = dht.readTemperature();
  float humidityValue = dht.readHumidity();
  int soilMoistureValue = analogRead(sensor_pin);
  float moisture_percentage = (100 - ((soilMoistureValue / 1024.0) * 100));

  // Mengatur nilai input fuzzy
  fuzzy->setInput(1, temperatureValue);
  fuzzy->setInput(2, moisture_percentage);

  // Menjalankan sistem fuzzy
  fuzzy->fuzzify();

  // Mendapatkan nilai output fuzzy
  float waterDecisionValue = fuzzy->defuzzify(1);

  if (WiFi.status() == WL_CONNECTED) {
    std::unique_ptr<BearSSL::WiFiClientSecure> client(new BearSSL::WiFiClientSecure);
    client->setInsecure();
    HTTPClient https;
    Serial.print("[HTTPS] begin...\n");

    if (https.begin(*client, String("https://") + host + "/api/api.php/")) {  // HTTPS
      Serial.print("[HTTPS] POST...\n");
      https.addHeader("Authorization", "Bearer 3c9d6f412019a23d7be9dd7ada99bba623fa05e84be22151941808411fcd");
      
      jsonDocument["kelembapan"] = String(moisture_percentage);
      jsonDocument["temperature"] = String(temperatureValue);

      if (waterDecisionValue > 50) {
        digitalWrite(relay, HIGH); // Hidupkan relay
        digitalWrite(ledPin_merah, HIGH); // Hidupkan lampu merah
        digitalWrite(ledPin_hijau, LOW); // Matikan lampu hijau
        digitalWrite(ledPin_kuning, LOW); // Matikan lampu kuning
        jsonDocument["status"] = String("Siram");
        Serial.println("Siram - Kering");
      } else {
        if (moisture_percentage >= 50 && moisture_percentage <= 70) {
          digitalWrite(relay, LOW); // Matikan relay
          digitalWrite(ledPin_hijau, HIGH); // Hidupkan lampu hijau
          digitalWrite(ledPin_kuning, LOW); // Matikan lampu kuning
          digitalWrite(ledPin_merah, LOW); // Matikan lampu merah
          Serial.println("Tidak Siram - Lembab");
          jsonDocument["status"] = String("Tidak Siram");
        } else if (moisture_percentage >= 71) {
          digitalWrite(relay, LOW); // Matikan relay
          digitalWrite(ledPin_hijau, LOW); // Hidupkan lampu hijau
          digitalWrite(ledPin_kuning, HIGH); // Hidupkan lampu kuning
          digitalWrite(ledPin_merah, LOW); // Matikan lampu merah
          Serial.println("Tidak Siram - Basah");
          jsonDocument["status"] = String("Tidak Siram");
        }
      }

      jsonDocument["humidity"] = String("");
      jsonDocument["value"] = String(waterDecisionValue);

      String jsonString;
      serializeJson(jsonDocument, jsonString);
      Serial.println(jsonString);

      int httpCode = https.POST(jsonString);

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
        }
      } else {
        Serial.printf("[HTTPS] POST... failed, error: %s\n", https.errorToString(httpCode).c_str());
      }
      https.end();
    }
  } else {
    Serial.println("WiFi not connected");
  }
  delay(1500); // Tunggu selama 2 detik sebelum membaca ulang
}
