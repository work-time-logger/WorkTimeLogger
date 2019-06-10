#include "interfaces/hal/hal_interface.h"
#include <Arduino.h>

void hal_init(struct hal_interface *t) {
    t->config.init(t->device);
    Serial.println("t->config.init(t->device);");
    t->ota.init(&t->config);
    Serial.println("t->ota.init(&t->config);");
    t->backend.init(&t->config);
    Serial.println("t->backend.init(&t->config);");

    t->buzzer.init();
    Serial.println("t->buzzer.init();");
    t->success_led.init();
    Serial.println("t->success_led.init();");
    t->error_led.init();
    Serial.println("t->error_led.init();");
    t->rfid.init();
    Serial.println("t->rfid.init();");
    t->rtc.init();
    Serial.println("t->rtc.init();");
}

void hal_event(struct hal_interface *t) {
    t->ota.event();

    t->rfid.event();
}
