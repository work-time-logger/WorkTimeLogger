#include "VoidLed.h"

void void_led_init();
void void_led_start(float seconds);
void void_led_stop();

static const struct led_interface void_led = {
    void_led_init,
    void_led_start,
    void_led_stop
};

const struct led_interface *void_led_get() {
    return &void_led;
}

void void_led_init() {

}

void void_led_start(float seconds) {

}

void void_led_stop() {

}