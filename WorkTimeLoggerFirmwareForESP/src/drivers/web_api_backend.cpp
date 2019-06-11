#include "web_api_backend.h"

void web_api_backend_init(const config_manager_interface * config);

PingResponse web_api_backend_ping();
QueryResponse web_api_backend_query(const String& card_id);
StartResponse web_api_backend_start(const String& card_id);
EndResponse web_api_backend_end(const String& card_id, const String& entry_id);

static const struct backend_interface web_api_backend = {
        web_api_backend_init,
        web_api_backend_ping,
        web_api_backend_query,
        web_api_backend_start,
        web_api_backend_end
};

const struct backend_interface *web_api_backend_get() {
    return &web_api_backend;
}

#include <debug.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClientSecureBearSSL.h>
#include "drivers/generic_config_manager.h"
#include <ArduinoJson.h>

const config_manager_interface * web_api_backend_config;

void web_api_backend_init(const config_manager_interface * config){
    web_api_backend_config = config;
}

// Fingerprint for demo URL, expires on June 2, 2019, needs to be updated well before this date
//const uint8_t fingerprint[20] = {0x5A, 0xCF, 0xFE, 0xF0, 0xF1, 0xA6, 0xF4, 0x5F, 0xD2, 0x11, 0x11, 0xC6, 0x1D, 0x2F, 0x0E, 0xBC, 0x39, 0x8D, 0x50, 0xE0};


void request(String path, bool post, void (*callback)(HTTPClient &, int)) {
    DEBUG_BACKEND("[HTTPS] START\n");
    std::unique_ptr<BearSSL::WiFiClientSecure>client(new BearSSL::WiFiClientSecure);
//    WiFiClient client;

//    client->setFingerprint(fingerprint);
    client->setInsecure();

    HTTPClient https;

    char path_buffer[150];
    path.toCharArray(path_buffer, 150);
    char endpoint[200];
    sprintf(endpoint, "%s%s", web_api_backend_config->get_server(), path_buffer);
    char bearer[120];
    sprintf(bearer, "Bearer %s", web_api_backend_config->get_token());


    DEBUG_BACKEND("endpoint=%s\n", endpoint);
    DEBUG_BACKEND("bearer=%s\n", bearer);

    DEBUG_BACKEND("[HTTPS] begin...\n");
    if (https.begin(*client, endpoint)) {
//     if (https.begin(client, endpoint)) {  // for HTTP
        https.addHeader("Accept", "application/json");
        https.addHeader("Authorization", bearer);

        DEBUG_BACKEND("[HTTPS] %s...\n", post ? "POST" : "GET");
        // start connection and send HTTP header
        int httpCode = post ? https.POST("") : https.GET();

        // httpCode will be negative on error
        if (httpCode > 0) {
            // HTTP header has been send and Server response header has been handled
            DEBUG_BACKEND("[HTTPS] GET... code: %d\n", httpCode);
            callback(https, httpCode);
        } else {
            Serial.printf("[HTTPS] GET... failed, error: %s\n", HTTPClient::errorToString(httpCode).c_str());
        }

        https.end();
    } else {
        Serial.printf("[HTTPS] Unable to connect\n");
    }
}


PingResponse WEBAPI_PING_RESPONSE = PingResponse();

PingResponse web_api_backend_ping() {
    DEBUG_BACKEND("\nweb_api_backend_ping():\n");
    request("ping", false, [](HTTPClient &http, int httpCode) -> void {
        if (httpCode == HTTP_CODE_OK) {
            String payload = http.getString();
            DynamicJsonBuffer jsonBuffer;
            JsonObject &json = jsonBuffer.parseObject(payload);
            #ifdef DEBUG_PORT
                #ifdef DEBUG_BACKEND
                    json.printTo(DEBUG_PORT);
                #endif
            #endif
            if (json.success()) {
                strcpy(WEBAPI_PING_RESPONSE.uuid, json["data"]["uuid"]);
                strcpy(WEBAPI_PING_RESPONSE.name, json["data"]["name"]);
                WEBAPI_PING_RESPONSE.is_active = json["data"]["is_active"];
            } else {
                Serial.println("failed to load json");
                DEBUG_BACKEND(payload.c_str());
            }
        }
    });

    DEBUG_BACKEND("\nWEBAPI_PING_RESPONSE[uuid] = %s\n", WEBAPI_PING_RESPONSE.uuid);
    DEBUG_BACKEND("WEBAPI_PING_RESPONSE[name] = %s\n", WEBAPI_PING_RESPONSE.name);
    DEBUG_BACKEND("WEBAPI_PING_RESPONSE[is_active] = %s\n\n", WEBAPI_PING_RESPONSE.is_active ? "true" : "false");

    return WEBAPI_PING_RESPONSE;
}

QueryResponse WEBAPI_QUERY_RESPONSE = QueryResponse();

QueryResponse web_api_backend_query(const String& card_id) {
    DEBUG_BACKEND("\nweb_api_backend_query('%s'):\n", card_id.c_str());
    WEBAPI_QUERY_RESPONSE.valid = false;
    request("card/" + card_id, false, [](HTTPClient &http, int httpCode) -> void {
        if (httpCode == HTTP_CODE_OK) {
            String payload = http.getString();
            DynamicJsonBuffer jsonBuffer;
            JsonObject &json = jsonBuffer.parseObject(payload);
            #ifdef DEBUG_PORT
                #ifdef DEBUG_BACKEND
                    json.printTo(DEBUG_PORT);
                #endif
            #endif
            if (json.success()) {
                strcpy(WEBAPI_QUERY_RESPONSE.employee, json["employee"].as<char *>());
                strcpy(WEBAPI_QUERY_RESPONSE.first_name, json["first_name"].as<char *>());
                strcpy(WEBAPI_QUERY_RESPONSE.last_name, json["last_name"].as<char *>());
                WEBAPI_QUERY_RESPONSE.worked_today = json["worked_today"].as<int>();
                WEBAPI_QUERY_RESPONSE.worked_period = json["worked_period"].as<int>();
                WEBAPI_QUERY_RESPONSE.open_entry_working = json["open_entry_working"].as<int>();
                strlcpy(WEBAPI_QUERY_RESPONSE.open_entry, json["open_entry"] | "NULL", sizeof(WEBAPI_QUERY_RESPONSE.open_entry));
                WEBAPI_QUERY_RESPONSE.has_invalid_entries = json["has_invalid_entries"].as<bool>();
                WEBAPI_QUERY_RESPONSE.valid = true;
            } else {
                Serial.println("failed to load json");
                DEBUG_BACKEND(payload.c_str());
            }
        }
    });

    DEBUG_BACKEND("\nWEBAPI_QUERY_RESPONSE[valid] = %s\n", WEBAPI_QUERY_RESPONSE.valid ? "true" : "false");
    DEBUG_BACKEND("WEBAPI_QUERY_RESPONSE[employee] = %s\n", WEBAPI_QUERY_RESPONSE.employee);
    DEBUG_BACKEND("WEBAPI_QUERY_RESPONSE[first_name] = %s\n", WEBAPI_QUERY_RESPONSE.first_name);
    DEBUG_BACKEND("WEBAPI_QUERY_RESPONSE[last_name] = %s\n", WEBAPI_QUERY_RESPONSE.last_name);
    DEBUG_BACKEND("WEBAPI_QUERY_RESPONSE[has_invalid_entries] = %s\n", WEBAPI_QUERY_RESPONSE.has_invalid_entries ? "true" : "false");
    DEBUG_BACKEND("WEBAPI_QUERY_RESPONSE[worked_period] = %d\n", WEBAPI_QUERY_RESPONSE.worked_period);
    DEBUG_BACKEND("WEBAPI_QUERY_RESPONSE[worked_today] = %d\n", WEBAPI_QUERY_RESPONSE.worked_today);
    DEBUG_BACKEND("WEBAPI_QUERY_RESPONSE[open_entry] = %s\n", WEBAPI_QUERY_RESPONSE.open_entry);
    DEBUG_BACKEND("WEBAPI_QUERY_RESPONSE[open_entry_working] = %d\n\n", WEBAPI_QUERY_RESPONSE.open_entry_working);

    return WEBAPI_QUERY_RESPONSE;
}

StartResponse WEBAPI_START_RESPONSE = StartResponse();

StartResponse web_api_backend_start(const String& card_id) {
    DEBUG_BACKEND("\nweb_api_backend_start('%s'):\n", card_id.c_str());
    WEBAPI_START_RESPONSE.valid = false;
    request("card/" + card_id + "/start", true, [](HTTPClient &http, int httpCode) -> void {
        if (httpCode == HTTP_CODE_OK) {
            String payload = http.getString();
            DynamicJsonBuffer jsonBuffer;
            JsonObject &json = jsonBuffer.parseObject(payload);
            #ifdef DEBUG_PORT
                #ifdef DEBUG_BACKEND
                    json.printTo(DEBUG_PORT);
                #endif
            #endif
            if (json.success()) {
                strcpy(WEBAPI_START_RESPONSE.start, json["start"].as<char *>());
                WEBAPI_START_RESPONSE.valid = true;
            } else {
                Serial.println("failed to load json");
                DEBUG_BACKEND(payload.c_str());
            }
        }
    });

    DEBUG_BACKEND("\nWEBAPI_START_RESPONSE[valid] = %s\n", WEBAPI_START_RESPONSE.valid ? "true" : "false");
    DEBUG_BACKEND("WEBAPI_START_RESPONSE[start] = %s\n\n", WEBAPI_START_RESPONSE.start);

    return WEBAPI_START_RESPONSE;
}

EndResponse WEBAPI_END_RESPONSE = EndResponse();

EndResponse web_api_backend_end(const String& card_id, const String& entry_id) {
    DEBUG_BACKEND("\nweb_api_backend_end('%s', '%s'):\n", card_id.c_str(), entry_id.c_str());
    WEBAPI_END_RESPONSE.valid = false;
    request("card/" + card_id + "/stop/" + entry_id, true, [](HTTPClient &http, int httpCode) -> void {
        if (httpCode == HTTP_CODE_OK) {
            String payload = http.getString();
            DynamicJsonBuffer jsonBuffer;
            JsonObject &json = jsonBuffer.parseObject(payload);
            #ifdef DEBUG_PORT
                #ifdef DEBUG_BACKEND
                    json.printTo(DEBUG_PORT);
                #endif
            #endif
            if (json.success()) {
                strcpy(WEBAPI_END_RESPONSE.start, json["start"].as<char *>());
                strcpy(WEBAPI_END_RESPONSE.end, json["end"].as<char *>());
                WEBAPI_END_RESPONSE.worked_minutes = json["worked_minutes"].as<int>();
                WEBAPI_END_RESPONSE.valid = true;
            } else {
                Serial.println("failed to load json");
                DEBUG_BACKEND(payload.c_str());
            }
        }
    });

    DEBUG_BACKEND("\nWEBAPI_END_RESPONSE[valid] = %s\n", WEBAPI_END_RESPONSE.valid ? "true" : "false");
    DEBUG_BACKEND("WEBAPI_END_RESPONSE[start] = %s\n", WEBAPI_END_RESPONSE.start);
    DEBUG_BACKEND("WEBAPI_END_RESPONSE[end] = %s\n", WEBAPI_END_RESPONSE.end);
    DEBUG_BACKEND("WEBAPI_END_RESPONSE[worked_minutes] = %d\n\n", WEBAPI_END_RESPONSE.worked_minutes);

    return WEBAPI_END_RESPONSE;
}