// Config/ConfigManager.cpp

#include "ConfigManager.h"

#include <FS.h>                   //this needs to be first, or it all crashes and burns...

#include <ESP8266WiFi.h>          //https://github.com/esp8266/Arduino

//needed for library
#include <DNSServer.h>
#include <ESP8266WebServer.h>
#include <WiFiManager.h>          //https://github.com/tzapu/WiFiManager
#include <ArduinoJson.h>          //https://github.com/bblanchon/ArduinoJson

char api_server[64] = "https://wtls.duma.dev/hw/";
char api_token[96];
char ota_password[64] = "ota";

bool shouldSaveConfig = false;

void startWifiManager(bool force);
void read_configuration_from_spiffs();
void save_configuration_to_spiffs();


void saveConfigCallback () {
//    Serial.println("Should save config");
    shouldSaveConfig = true;
}

void CONFIG_FORCE() {
    startWifiManager(true);

}

void CONFIG_INIT() {
    startWifiManager(false);
}

void startWifiManager(bool force) {
    read_configuration_from_spiffs();

    WiFiManagerParameter custom_api_server("server", "API Server Address", api_server, 60);
    WiFiManagerParameter custom_api_token("token", "API Server Token", api_token, 80);
    WiFiManagerParameter custom_ota_password("ota_password", "OTA Updater Password", ota_password, 60);

    WiFiManager wifiManager;
    wifiManager.setDebugOutput(false);
    wifiManager.setMinimumSignalQuality(50);
    wifiManager.setTimeout(120);
    wifiManager.setSaveConfigCallback(saveConfigCallback);
    wifiManager.addParameter(&custom_api_token);
    wifiManager.addParameter(&custom_api_server);
    wifiManager.addParameter(&custom_ota_password);

    if(force) {
        ESP.eraseConfig();
        wifiManager.resetSettings();
        ESP.reset();
    }

    if (
            (force && !wifiManager.startConfigPortal("WorkTime Logger Configuration", "password"))
            ||
            (!force && !wifiManager.autoConnect("WorkTime Logger Configuration", "password"))
        ) {
        Serial.println("failed to connect and hit timeout");
        delay(3000);
        //reset and try again, or maybe put it to deep sleep
        ESP.reset();
        delay(5000);
    }

    strcpy(api_server, custom_api_server.getValue());
    strcpy(api_token, custom_api_token.getValue());
    strcpy(ota_password, custom_ota_password.getValue());
    save_configuration_to_spiffs();
}

void save_configuration_to_spiffs() {
    //save the custom parameters to FS
    if (shouldSaveConfig) {
        DynamicJsonBuffer jsonBuffer;
        JsonObject &json = jsonBuffer.createObject();
        json["api_server"] = api_server;
        json["api_token"] = api_token;
        json["ota_password"] = ota_password;

        File configFile = SPIFFS.open("/config.json", "w");
        if (!configFile) {
            Serial.println("failed to open config file for writing");
        }

//        json.printTo(Serial);
        json.printTo(configFile);
        configFile.close();
    }
}

void read_configuration_from_spiffs() {
    if (SPIFFS.begin()) {
        if (SPIFFS.exists("/config.json")) {
            //file exists, reading and loading
            File configFile = SPIFFS.open("/config.json", "r");
            if (configFile) {
                size_t size = configFile.size();
                // Allocate a buffer to store contents of the file.
                std::unique_ptr<char[]> buf(new char[size]);

                configFile.readBytes(buf.get(), size);
                DynamicJsonBuffer jsonBuffer;
                JsonObject &json = jsonBuffer.parseObject(buf.get());
//                json.printTo(Serial);
                if (json.success()) {
                    strcpy(api_server, json["api_server"]);
                    strcpy(api_token, json["api_token"]);
                    strcpy(ota_password, json["ota_password"]);
                } else {
                    Serial.println("failed to load json config");
                }
                configFile.close();
            }
        }
    } else {
        Serial.println("failed to mount FS");
    }
}

char *CONFIG_GET_TOKEN() {
    return api_token;
}

char *CONFIG_GET_SERVER() {
    return api_server;
}

char *CONFIG_GET_OTA_PASSWORD() {
    return ota_password;
}
