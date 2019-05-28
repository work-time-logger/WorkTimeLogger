#include <Modules/RFID.h>
#include <Modules/HMI.h>
#include <Modules/Scheduler.h>
#include <Modules/Buzzer.h>
#include "CardReader.h"
#include "Workflow.h"

char last_read_card[50];
QueryResponse last_query_response;

void cardRead(char read_card[])
{
    strcpy(last_read_card, read_card);

    if(workflow_stage != IDLE)
        return;


    last_query_response = WEBAPI_QUERY(read_card);

    if(!last_query_response.valid) {
        BUZZER_START(2);
        return;
    }

    BUZZER_START(0.25);

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
        char buf2[50];
        sprintf(buf2, "%s min.", itoa(last_query_response.worked_today, buf, 10));
        page_exit_text_day.setText(buf2);
    }


}

void CARDREADER_INIT() {
    mfrc522_read_callback = cardRead;
    RFID_INIT();
}

void CARDREADER_EVENT() {
    RFID_EVENT();
}
