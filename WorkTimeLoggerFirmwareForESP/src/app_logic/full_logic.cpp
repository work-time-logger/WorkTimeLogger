#include "full_logic.h"
#include "interfaces/hal/hal_interface.h"

#include "app_logic/full/hmi.h"
#include "app_logic/full/clock.h"
#include "app_logic/full/full_workflow.h"

void full_logic_init(const hal_interface * device);
void full_logic_event(const hal_interface * device);

static const struct main_logic_interface full_logic = {
    full_logic_init,
    full_logic_event
};

const struct main_logic_interface *full_logic_get() {
    return &full_logic;
}

void full_logic_init(const hal_interface * device){
    clock_start();
    hmi_init();
    full_workflow_init();
}

void full_logic_event(const hal_interface * device){
    hmi_event();
    full_workflow_event();
}