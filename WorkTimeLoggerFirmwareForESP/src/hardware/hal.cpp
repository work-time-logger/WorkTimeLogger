#include <debug.h>

#include "interfaces/hal/hal_interface.h"

#include <Arduino.h>

void hal_init(struct hal_interface *t) {
    t->config.init(t->device);
    DEBUG_APP("t->config.init(t->device);\n");

    t->ota.init(&t->config);
    DEBUG_APP("t->ota.init(&t->config);\n");

    t->backend.init(&t->config);
    DEBUG_APP("t->backend.init(&t->config);\n");


    t->buzzer.init();
    DEBUG_APP("t->buzzer.init();\n");

    t->success_led.init();
    DEBUG_APP("t->success_led.init();\n");

    t->error_led.init();
    DEBUG_APP("t->error_led.init();\n");

    t->rfid.init();
    DEBUG_APP("t->rfid.init();\n");

    t->rtc.init();
    DEBUG_APP("t->rtc.init();\n");
}

void hal_event(struct hal_interface *t) {
    t->ota.event();

    t->rfid.event();
}
