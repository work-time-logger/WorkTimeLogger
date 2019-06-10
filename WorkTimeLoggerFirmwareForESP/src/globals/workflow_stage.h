#ifndef GLOBAL_WORKFLOW_STAGE_H
#define GLOBAL_WORKFLOW_STAGE_H

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
extern workflow_stage_enum workflow_last_stage;

#endif //GLOBAL_WORKFLOW_STAGE_H
