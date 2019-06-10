#include "generic_config_manager.h"

void generic_config_manager_init(const device_capabilities * device);
void generic_config_manager_force();
char * generic_config_manager_get_server();
char * generic_config_manager_get_token();
char * generic_config_manager_get_ota_password();

static const struct config_manager_interface generic_config_manager = {
        generic_config_manager_init,
        generic_config_manager_force,
        generic_config_manager_get_server,
        generic_config_manager_get_token,
        generic_config_manager_get_ota_password
};

const struct config_manager_interface *generic_config_manager_get() {
    return &generic_config_manager;
}


#include <IPAddress.h>
#include <FS.h>                   //this needs to be first, or it all crashes and burns...
#include <ESP8266WiFi.h>          //https://github.com/esp8266/Arduino

//needed for library
#include <DNSServer.h>
#include <ESP8266WebServer.h>
#include <WiFiManager.h>          //https://github.com/tzapu/WiFiManager
#include <ArduinoJson.h>          //https://github.com/bblanchon/ArduinoJson

const device_capabilities * current_device;
char api_server[64] = "https://wtls.duma.dev/hw/";
char api_token[96];
char ota_password[64] = "ota";

bool shouldSaveConfig = false;

void startWifiManager(bool force);
void read_configuration_from_spiffs();
void save_configuration_to_spiffs();

void generic_config_manager_init(const device_capabilities * device) {
    current_device = device;
    startWifiManager(false);
}

void generic_config_manager_force() {
    startWifiManager(true);
}

char * generic_config_manager_get_server() {
    return api_server;
}

char * generic_config_manager_get_token() {
    return api_token;
}

char * generic_config_manager_get_ota_password() {
    return ota_password;
}


void saveConfigCallback () {
    shouldSaveConfig = true;
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
            (force && !wifiManager.startConfigPortal(current_device->wifi_name, current_device->wifi_password))
            ||
            (!force && !wifiManager.autoConnect(current_device->wifi_name, current_device->wifi_password))
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