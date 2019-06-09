// App/Workflow.cpp

#include <Modules/HMI.h>
#include <Modules/Scheduler.h>
#include <Modules/Buzzer.h>
#include <Config/ConfigManager.h>
#include <Modules/WebApi.h>
#include "Workflow.h"
#include "CardReader.h"
#include "StringHelper.h"

workflow_stage_enum workflow_stage = IDLE;
workflow_stage_enum workflow_last_stage = UNKNOWN;

void no_button_callback(void *ptr) {
    BUZZER_START(0.01);
    if (workflow_stage != ENTER && workflow_stage != EXIT) return;
    page_main.show();
    workflow_stage = IDLE;
}

void config_button_callback(void *ptr) {
    page_main_text_scroll.setText("Starting Configuration!");
    BUZZER_START(1);
    delay(3000);
    CONFIG_FORCE();
    ESP.reset();
}

void exit_stats() {
    if (workflow_stage != STATS) return;

    page_main.show();
    workflow_stage = IDLE;
}

void yes_button_callback(void *ptr) {
    BUZZER_START(0.01);
    if(workflow_stage == ENTER){
        WEBAPI_START(last_read_card);
        page_main.show();
        workflow_stage = IDLE;

        return;
    }

    if(workflow_stage == EXIT){
        WEBAPI_END(last_read_card, last_query_response.open_entry);
        char buf[50];
        last_query_response = WEBAPI_QUERY(last_read_card);
        page_stats.show();
        format_minutes(last_query_response.worked_today, buf);
        page_stats_text_day.setText(buf);
        format_minutes(last_query_response.worked_period, buf);
        page_stats_text_period.setText(buf);
        workflow_stage = STATS;
        workflow_scheduler.once(5, exit_stats);

        return;
    }
}

void WORKFLOW_INIT() {
    page_main_button_config.attachPop(config_button_callback, &page_main_button_config);
    page_enter_button_no.attachPop(no_button_callback, &page_enter_button_no);
    page_enter_button_yes.attachPop(yes_button_callback, &page_enter_button_yes);
    page_exit_button_no.attachPop(no_button_callback, &page_exit_button_no);
    page_exit_button_yes.attachPop(yes_button_callback, &page_exit_button_yes);
}

void WORKFLOW_EVENT() {
    if(workflow_last_stage != workflow_stage){
        if(workflow_stage == IDLE)
            sendCommand("dim=5");
        else
            sendCommand("dim=100");


        workflow_last_stage = workflow_stage;
    }

}
