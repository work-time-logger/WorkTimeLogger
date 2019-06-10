#include "generic_builtin_led.h"

void generic_builtin_led_init();
void generic_builtin_led_start(float seconds);
void generic_builtin_led_stop();

static const struct led_interface generic_builtin_led = {
    generic_builtin_led_init,
    generic_builtin_led_start,
    generic_builtin_led_stop
};

const struct led_interface *generic_builtin_led_get() {
    return &generic_builtin_led;
}

#include <Arduino.h>
#include <Ticker.h>

Ticker generic_builtin_led_ticker; // NOLINT(cert-err58-cpp)

void generic_builtin_led_init() {
    pinMode(LED_BUILTIN, OUTPUT);
    digitalWrite(LED_BUILTIN, HIGH);
}

void generic_builtin_led_start(float seconds) {
    digitalWrite(LED_BUILTIN, LOW);

    generic_builtin_led_ticker.once(seconds, generic_builtin_led_stop);
}

void generic_builtin_led_stop() {
    digitalWrite(LED_BUILTIN, HIGH);
}