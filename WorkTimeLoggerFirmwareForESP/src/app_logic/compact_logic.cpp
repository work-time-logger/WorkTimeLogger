#include "interfaces/hal/hal_interface.h"
#include "compact_logic.h"
#include "compact/compact_workflow.h"

void compact_logic_init(const hal_interface * device);
void compact_logic_event(const hal_interface * device);

static const struct main_logic_interface compact_logic = {
    compact_logic_init,
    compact_logic_event
};

const struct main_logic_interface *compact_logic_get() {
    return &compact_logic;
}

void compact_logic_init(const hal_interface * device){
    compact_workflow_init();
}

void compact_logic_event(const hal_interface * device){
    compact_workflow_event();
}