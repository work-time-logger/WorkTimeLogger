// Modules/HMI.cpp

#pragma clang diagnostic push
#pragma ide diagnostic ignored "cert-err58-cpp"

#include "HMI.h"

NexPage page_main = NexPage(0, 0, "page0");
NexText page_main_text_scroll = NexText(0, 2, "g0");
NexText page_main_text_time = NexText(0, 1, "time");
NexButton page_main_button_config = NexButton(0, 3, "b0");

NexPage page_enter = NexPage(1, 0, "page1");
NexButton page_enter_button_no = NexButton(1, 3, "no");
NexButton page_enter_button_yes = NexButton(1, 4, "yes");


NexPage page_exit = NexPage(2, 0, "page2");
NexButton page_exit_button_no = NexButton(2, 3, "no");
NexButton page_exit_button_yes = NexButton(2, 4, "yes");

NexText page_enter_exit_text_name = NexText(5, 0, "name");
NexText page_exit_text_day = NexText(8, 0, "day");

NexPage page_stats = NexPage(3, 0, "page3");
NexText page_stats_text_day = NexText(2, 0, "day");
NexText page_stats_text_period = NexText(3, 0, "period");

NexTouch *nex_listen_list[] =
{
//    &page_main,
//    &page_enter,
//    &page_exit,
//    &page_stats,
    &page_main_button_config,
    &page_enter_button_no,
    &page_enter_button_yes,
    &page_exit_button_no,
    &page_exit_button_yes,
//    &page_enter_exit_text_name,
//    &page_exit_text_day,
//    &page_stats_text_day,
//    &page_stats_text_period,
//    &page_main_text_scroll,
//    &page_main_text_time,
    NULL
};

void HMI_INIT() {
    nexInit();
}

void HMI_EVENT() {
    nexLoop(nex_listen_list);
}

#pragma clang diagnostic pop