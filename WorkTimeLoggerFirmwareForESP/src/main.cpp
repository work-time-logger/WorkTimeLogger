#pragma clang diagnostic push
#pragma ide diagnostic ignored "cert-err58-cpp"
//#include <ESP8266WiFi.h>
//#include <WiFiClient.h>
//#include <ESP8266WebServer.h>
//#include <ESP8266mDNS.h>
//
//const char* ssid = "iot.caston.pl";
//const char* password = "W9TNQic9";
//
//ESP8266WebServer server(80);
//
//const int led = LED_BUILTIN;
//
//void handleRoot() {
//    digitalWrite(led, 1);
//    Serial.println("GET /");
//    server.send(200, "text/plain", "hello from esp8266!");
//    digitalWrite(led, 0);
//}
//
//void handleNotFound(){
//    digitalWrite(led, 1);
//    String message = "File Not Found\n\n";
//    message += "URI: ";
//    message += server.uri();
//    message += "\nMethod: ";
//    message += (server.method() == HTTP_GET)?"GET":"POST";
//    message += "\nArguments: ";
//    message += server.args();
//    message += "\n";
//    for (uint8_t i=0; i<server.args(); i++){
//        message += " " + server.argName(i) + ": " + server.arg(i) + "\n";
//    }
//    Serial.println(message);
//    server.send(404, "text/plain", message);
//    digitalWrite(led, 0);
//}
//
//void setup(void){
//    pinMode(led, OUTPUT);
//    digitalWrite(led, 0);
//    Serial.begin(115200);
//    WiFi.mode(WIFI_STA);
//    WiFi.begin(ssid, password);
//    Serial.println("");
//
//    // Wait for connection
//    while (WiFi.status() != WL_CONNECTED) {
//        delay(500);
//        Serial.print(".");
//    }
//    Serial.println("");
//    Serial.print("Connected to ");
//    Serial.println(ssid);
//    Serial.print("IP address: ");
//    Serial.println(WiFi.localIP());
//
//    if (MDNS.begin("esp8266")) {
//        Serial.println("MDNS responder started");
//    }
//
//    server.on("/", handleRoot);
//
//    server.on("/inline", [](){
//
//        Serial.println("GET /inline");
//        server.send(200, "text/plain", "this works as well");
//    });
//
//    server.onNotFound(handleNotFound);
//
//    server.begin();
//    Serial.println("HTTP server started");
//}
//
//void loop(void){
//    server.handleClient();
//}


#include <Arduino.h>
#include <Modules/HMI.h>
#include <Modules/Scheduler.h>
#include <Modules/RFID.h>
#include <Modules/RTC.h>
#include <Wire.h>
#include <App/Clock.h>
#include <App/CardReader.h>
#include <App/Workflow.h>


#include <Config/ConfigManager.h>
#include <Config/OverTheAirUpdater.h>
#include <Modules/Buzzer.h>
#include <Modules/WebApi.h>


//=======================================================================
//void changeState()
//{
////    static int i = 0;
//    digitalWrite(LED_BUILTIN, !(digitalRead(LED_BUILTIN)));  //Invert Current State of LED_BUILTIN
//
//    BUZZER_START(0.25);
//
////    page_stats.show();
//
////    char bufor[16];
////    page_stats_text_day.setText(itoa(i++, bufor, 10));
//}
//=======================================================================



void setup(void)
{
    pinMode(LED_BUILTIN,OUTPUT);
    Serial.begin(9600);
    Wire.begin();

    CONFIG_INIT();
    OTA_INIT();
    BUZZER_INIT();
    HMI_INIT();
    WORKFLOW_INIT();
    CARDREADER_INIT();
    CLOCK_INIT();
//    scheduler.attach(10, changeState);


//    auto start = WEBAPI_START("74-0B-2A-EB");
//    delay(60000);
//
//    auto query = WEBAPI_QUERY("74-0B-2A-EB");
//    Serial.println(query.open_entry);
//
//    auto stop = WEBAPI_END("74-0B-2A-EB", query.open_entry);
//    Serial.println(stop.start);
//    Serial.println(stop.end);
//    Serial.print("stop.worked_minutes = ");
//    Serial.print(stop.worked_minutes);
//    Serial.println(";");
}

void loop(void)
{
    OTA_EVENT();
    CARDREADER_EVENT();
    HMI_EVENT();
    WORKFLOW_EVENT();
}

#pragma clang diagnostic pop