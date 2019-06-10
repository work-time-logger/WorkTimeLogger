#include "full_buzzer.h"

void full_buzzer_init();
void full_buzzer_start(float seconds);
void full_buzzer_stop();

static const struct buzzer_interface full_buzzer = {
    full_buzzer_init,
    full_buzzer_start,
    full_buzzer_stop
};

const struct buzzer_interface *full_buzzer_get() {
    return &full_buzzer;
}

#include <Arduino.h>
#include <PCF8574.h>
#include <Ticker.h>

Ticker full_buzzer_ticker; // NOLINT(cert-err58-cpp)

PCF8574 full_buzzer_expander; // NOLINT(cert-err58-cpp)

void full_buzzer_init() {
    full_buzzer_expander.begin(0x20);
    full_buzzer_expander.pinMode(0, OUTPUT);
    full_buzzer_expander.digitalWrite(0, HIGH);
}

void full_buzzer_start(float seconds) {
    full_buzzer_expander.digitalWrite(0, LOW);

    full_buzzer_ticker.once(seconds, full_buzzer_stop);
}

void full_buzzer_stop() {
    full_buzzer_expander.digitalWrite(0, HIGH);
}