// Modules/Buzzer.cpp

#include <Arduino.h>
#include <PCF8574.h>
#include "Buzzer.h"
#include "Scheduler.h"

/** PCF8574 instance */
PCF8574 expander;

void BUZZER_INIT()
{
    expander.begin(0x20);
    expander.pinMode(0, OUTPUT);
    expander.digitalWrite(0, HIGH);
}

void stop_buzzer() {
    expander.digitalWrite(0, HIGH);
}

void BUZZER_START(float seconds)
{
    expander.digitalWrite(0, LOW);

    buzzer_scheduler.once(seconds, stop_buzzer);
}
