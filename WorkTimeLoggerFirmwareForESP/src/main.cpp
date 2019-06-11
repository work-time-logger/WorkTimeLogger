#include <Arduino.h>
#include <Wire.h>

#include <interfaces/hal/hal_interface.h>
#include <interfaces/app_logic/main_logic_interface.h>

#ifndef HARDWARE_VERSION
    #error "No valid HARDWARE_VERSION provided"
#elif HARDWARE_VERSION == compact
    #include <hardware/compact_hal.h>
    #include <app_logic/compact_logic.h>
#elif HARDWARE_VERSION == full
    #include <hardware/full_hal.h>
    #include <app_logic/full_logic.h>
#else
    #error "Unknown HARDWARE_VERSION provided"
#endif

#include <globals/hal.h>

main_logic_interface logic;

void setup(void)
{
    Serial.begin(9600);

    #ifndef HARDWARE_VERSION
        #error "No valid HARDWARE_VERSION provided"
    #elif HARDWARE_VERSION == compact
        hal = *compact_hal_get();
        logic = *compact_logic_get();
    #elif HARDWARE_VERSION == full
        hal = *full_hal_get();
        logic = *full_logic_get();
    #else
        #error "Unknown HARDWARE_VERSION provided"
    #endif






//    hal.rfid.set_callback(cardReadT);
//    Serial.println(hal.device->name);
    hal_init(&hal);
    logic.init(&hal);
//    Serial.println(hal.config.get_server());


//    auto start = hal.backend.start("74-0B-2A-EB");
//    Serial.println(start.start);
//    delay(60000);

//    auto query = hal.backend.query("74-0B-2A-EB");
//    Serial.println(query.open_entry);
//    Serial.println(query.first_name);
//    Serial.println(query.last_name);
//    Serial.println(query.worked_period);

//    auto stop = hal.backend.end("74-0B-2A-EB", query.open_entry);
//    Serial.println(stop.start);
//    Serial.println(stop.end);
//    Serial.print("stop.worked_minutes = ");
//    Serial.print(stop.worked_minutes);
//    Serial.println(";");

//    scheduler.attach(10, changeState);
}

void loop(void)
{
    hal_event(&hal);
    logic.event(&hal);
}