#include <Modules/RTOS.h>
#include <Modules/RTC.h>
#include <Modules/HMI.h>

#include "Clock.h"
#include "Workflow.h"

void refresh_clock() {
    static bool time = true;
    static int timer = CLOCK_TICKS;

    if(workflow_stage != IDLE)
        return;

    if(time)
        page_main_text_time.setText(
            RTC_TIME(
                timer%2?":":" "
            )
        );
    else
        page_main_text_time.setText(
            RTC_DATE()
        );

    if(!--timer)
    {
        time = !time;
        timer = time ? CLOCK_TICKS : DATE_TICKS;
    }
}

void CLOCK_INIT() {
    RTC_INIT();
    clock_scheduler.attach(0.5, refresh_clock);
}
