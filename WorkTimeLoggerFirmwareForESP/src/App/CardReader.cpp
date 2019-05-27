#include <Modules/RFID.h>
#include <Modules/HMI.h>
#include <Modules/Scheduler.h>
#include <Modules/Buzzer.h>
#include "CardReader.h"
#include "Workflow.h"



void cardRead(char read_card[])
{
    if(workflow_stage != IDLE)
        return;

    page_main_text_scroll.setText(read_card);

    BUZZER_START(0.25);

    workflow_stage = EXIT;
    page_exit.show();
    page_enter_exit_text_name.setText(read_card);
}

void CARDREADER_INIT() {
    mfrc522_read_callback = cardRead;
    RFID_INIT();
}

void CARDREADER_EVENT() {
    RFID_EVENT();
}
