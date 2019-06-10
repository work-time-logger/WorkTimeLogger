#include "Interfaces/HALInterface.h"

void hal_init(struct hal_interface *t) {
    t->config.init(t->device);
    t->ota.init(&t->config);
    t->backend.init(&t->config);

    t->buzzer.init();
    t->success_led.init();
    t->error_led.init();
    t->rfid.init();
    t->rtc.init();
}

void hal_event(struct hal_interface *t) {
    t->ota.event();

    t->rfid.event();
}
