#include "VoidRtc.h"

void void_rtc_init(void);
char* void_rtc_get_time(const char *separator = ":", bool dont_refresh = false);
char* void_rtc_get_date(const char *separator = "/", bool dont_refresh = false);

static const struct rtc_interface void_led = {
    void_rtc_init,
    void_rtc_get_time,
    void_rtc_get_date
};

const struct rtc_interface *void_rtc_get() {
    return &void_led;
}

void void_rtc_init(void){

}

char* void_rtc_get_time(const char *separator, bool dont_refresh){
    return nullptr;
}

char* void_rtc_get_date(const char *separator, bool dont_refresh){
    return nullptr;
}