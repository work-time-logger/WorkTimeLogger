#ifndef RTC_INTERFACE_H
#define RTC_INTERFACE_H

typedef void (*rtc_init)(void);
typedef char* (*rtc_get_time)(const char *separator, bool dont_refresh);
typedef char* (*rtc_get_date)(const char *separator, bool dont_refresh);

struct rtc_interface
{
    rtc_init init;
    rtc_get_time get_time;
    rtc_get_date get_date;
};

#endif //RTC_INTERFACE_H
