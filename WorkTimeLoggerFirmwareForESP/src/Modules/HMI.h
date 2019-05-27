#ifndef HMI_H
#define HMI_H

#include <Nextion.h>

extern NexPage page_main;
extern NexText page_main_text_scroll;
extern NexText page_main_text_time;

extern NexPage page_enter;
extern NexPage page_exit;
extern NexButton page_main_button_config;
extern NexButton page_enter_button_no;
extern NexButton page_enter_button_yes;
extern NexButton page_exit_button_no;
extern NexButton page_exit_button_yes;
extern NexText page_enter_exit_text_name;
extern NexText page_exit_text_day;

extern NexPage page_stats;
extern NexText page_stats_text_day;
extern NexText page_stats_text_period;

void HMI_INIT();
void HMI_EVENT();

#endif //HMI_H
