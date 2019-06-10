#include "compact_success_led.h"

void compact_success_led_init();
void compact_success_led_start(float seconds);
void compact_success_led_stop();

static const struct led_interface compact_success_led = {
    compact_success_led_init,
    compact_success_led_start,
    compact_success_led_stop
};

const struct led_interface *compact_success_led_get() {
    return &compact_success_led;
}

#include <Arduino.h>
#include <Ticker.h>

Ticker compact_success_led_ticker; // NOLINT(cert-err58-cpp)

void compact_success_led_init() {
    pinMode(D2, OUTPUT);
    digitalWrite(D2, HIGH);
}

void compact_success_led_start(float seconds) {
    digitalWrite(D2, LOW);

    compact_success_led_ticker.once(seconds, compact_success_led_stop);
}

void compact_success_led_stop() {
    digitalWrite(D2, HIGH);
}