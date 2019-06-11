#include "compact_workflow.h"

#include "globals/hal.h"

#include <Ticker.h>

void compact_card_read_callback(char read_card[])
{
    auto query = hal.backend.query(read_card);

    if(!query.valid) {
        hal.buzzer.start(4);
        return;
    }

    String open_entry = query.open_entry;

    if(open_entry =="NULL"){
        hal.buzzer.start(0.05);
        hal.success_led.start(6);
        hal.backend.start(read_card);
        hal.buzzer.start(0.05);
        hal.success_led.start(1);
    } else {
        hal.buzzer.start(0.05);
        hal.error_led.start(6);
        hal.backend.end(read_card, open_entry);
        hal.buzzer.start(0.75);
        hal.error_led.start(1);
    }
}

void compact_workflow_init() {
    hal.rfid.set_callback(compact_card_read_callback);
}

void compact_workflow_event() {

}
