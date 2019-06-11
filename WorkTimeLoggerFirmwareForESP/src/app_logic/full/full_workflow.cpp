#include "full_workflow.h"

#include "globals/hal.h"
#include "globals/workflow_stage.h"

#include "helpers/string_helpers.h"

#include "app_logic/full/hmi.h"

#include <Ticker.h>

Ticker workflow_scheduler; // NOLINT(cert-err58-cpp)
char last_read_card[50];
QueryResponse last_query_response;

void no_button_callback(void *ptr) {
    hal.buzzer.start(0.01);
    if (workflow_stage != ENTER && workflow_stage != EXIT) return;
    page_main.show();
    workflow_stage = IDLE;
}

void config_button_callback(void *ptr) {
    page_main_text_scroll.setText("Starting Configuration!");
    hal.buzzer.start(1);
    delay(3000);
    hal.config.force();
    ESP.reset();
}

void exit_stats() {
    if (workflow_stage != STATS) return;

    page_main.show();
    workflow_stage = IDLE;
}

void yes_button_callback(void *ptr) {
    hal.buzzer.start(0.01);
    if(workflow_stage == ENTER){
        hal.backend.start(last_read_card);
        page_main.show();
        workflow_stage = IDLE;

        return;
    }

    if(workflow_stage == EXIT){
        hal.backend.end(last_read_card, last_query_response.open_entry);
        char buf[50];
        last_query_response = hal.backend.query(last_read_card);
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

void full_card_reader_callback(char read_card[])
{
    strcpy(last_read_card, read_card);

    if(workflow_stage != IDLE)
        return;

    last_query_response = hal.backend.query(read_card);

    if(!last_query_response.valid) {
        hal.buzzer.start(2);
        return;
    }

    hal.buzzer.start(0.25);

    char name[50];
    sprintf(name, "%s %s", last_query_response.first_name, last_query_response.last_name);

    String open_entry = last_query_response.open_entry;

    if(open_entry =="NULL"){
        workflow_stage = ENTER;
        page_enter.show();
        page_enter_exit_text_name.setText(name);
    } else {
        workflow_stage = EXIT;
        page_exit.show();
        page_enter_exit_text_name.setText(name);
        char buf[50];
        format_minutes(last_query_response.open_entry_working, buf);
        page_exit_text_day.setText(buf);
    }
}

void full_workflow_init() {
    page_main_button_config.attachPop(config_button_callback, &page_main_button_config);
    page_enter_button_no.attachPop(no_button_callback, &page_enter_button_no);
    page_enter_button_yes.attachPop(yes_button_callback, &page_enter_button_yes);
    page_exit_button_no.attachPop(no_button_callback, &page_exit_button_no);
    page_exit_button_yes.attachPop(yes_button_callback, &page_exit_button_yes);

    hal.rfid.set_callback(full_card_reader_callback);
}

void full_workflow_event() {
    if(workflow_last_stage != workflow_stage){
        if(workflow_stage == IDLE)
            sendCommand("dim=5");
        else
            sendCommand("dim=100");

        workflow_last_stage = workflow_stage;
    }
}
