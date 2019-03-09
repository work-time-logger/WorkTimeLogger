#ifndef WORKFLOW_H
#define WORKFLOW_H

enum workflow_stage_enum {
    IDLE,
    ENTER,
    EXIT,
    STATS,
    ERROR_MESSAGE,
    SUCCESS_MESSAGE,
    UNKNOWN
};

extern workflow_stage_enum workflow_stage;

void WORKFLOW_INIT();
void WORKFLOW_EVENT();

#endif //WORKFLOW_H
