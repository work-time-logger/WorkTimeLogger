#include "CompactErrorLed.h"

void compact_error_led_init();
void compact_error_led_start(float seconds);
void compact_error_led_stop();

static const struct led_interface compact_error_led = {
    compact_error_led_init,
    compact_error_led_start,
    compact_error_led_stop
};

const struct led_interface *compact_error_led_get() {
    return &compact_error_led;
}

#include <Arduino.h>
#include <Ticker.h>

Ticker compact_error_led_ticker; // NOLINT(cert-err58-cpp)

void compact_error_led_init() {
    pinMode(D1, OUTPUT);
    digitalWrite(D1, HIGH);
}

void compact_error_led_start(float seconds) {
    digitalWrite(D1, LOW);

    compact_error_led_ticker.once(seconds, compact_error_led_stop);
}

void compact_error_led_stop() {
    digitalWrite(D1, HIGH);
}