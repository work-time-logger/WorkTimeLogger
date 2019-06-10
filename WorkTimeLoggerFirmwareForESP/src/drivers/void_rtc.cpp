#include "void_rtc.h"

void void_rtc_init(void);
char* void_rtc_get_time(const char *separator = ":", bool dont_refresh = false);
char* void_rtc_get_date(const char *separator = "/", bool dont_refresh = false);

static const struct rtc_interface void_rtc = {
    void_rtc_init,
    void_rtc_get_time,
    void_rtc_get_date
};

const struct rtc_interface *void_rtc_get() {
    return &void_rtc;
}

void void_rtc_init(void){

}

char* void_rtc_get_time(const char *separator, bool dont_refresh){
    return nullptr;
}

char* void_rtc_get_date(const char *separator, bool dont_refresh){
    return nullptr;
}