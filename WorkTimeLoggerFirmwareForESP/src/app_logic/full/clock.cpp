#include "clock.h"

#include <Ticker.h>
#include <globals/hal.h>
#include "hmi.h"
#include "globals/workflow_stage.h"

Ticker clock_scheduler;

void refresh_clock() {
    static bool time = true;
    static int timer = CLOCK_TICKS;

    if(workflow_stage != IDLE)
        return;

    if(time)
        page_main_text_time.setText(
            hal.rtc.get_time(
                timer%2?":":" ",
                false
            )
        );
    else
        page_main_text_time.setText(
            hal.rtc.get_date(
                "/",
                false
            )
        );

    if(!--timer)
    {
        time = !time;
        timer = time ? CLOCK_TICKS : DATE_TICKS;
    }
}

void clock_start() {
    clock_scheduler.attach(5, refresh_clock);
}
