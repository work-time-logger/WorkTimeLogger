#include <Modules/RFID.h>
#include <Modules/HMI.h>
#include <Modules/RTOS.h>
#include "CardReader.h"
#include "Workflow.h"

void stop_buzzer() {
    digitalWrite(BUZZER_PIN, 0);
}

void cardRead(char read_card[])
{
    if(workflow_stage != IDLE)
        return;

    page_main_text_scroll.setText(read_card);

    workflow_stage = EXIT;
    page_exit.show();
    page_enter_exit_text_name.setText(read_card);

    digitalWrite(BUZZER_PIN, 1);
    buzzer_scheduler.once(0.5, stop_buzzer);
}

void CARDREADER_INIT() {
    pinMode(BUZZER_PIN,OUTPUT);
    digitalWrite(BUZZER_PIN, 0);
    mfrc522_read_callback = cardRead;
    RFID_INIT();
}

void CARDREADER_EVENT() {
    RFID_EVENT();
}
