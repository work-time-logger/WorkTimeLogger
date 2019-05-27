#include "RTC.h"

RTC_DS1307 RTC;
char rtc_read_time[8];
char rtc_read_date[10];
DateTime now;

void RTC_INIT() {
    RTC.begin();
    if (! RTC.isrunning()) {
        Serial.println("RTC is NOT running!");
        // following line sets the RTC to the date & time this sketch was compiled
        RTC.adjust(DateTime(__DATE__, __TIME__));
    }
}

void RTC_READ() {
    now = RTC.now();
}

char *RTC_TIME(const char *separator, bool dont_refresh) {
    rtc_read_time[0] = 0;
    if(!dont_refresh)
        RTC_READ();

    char bufor[16];
    if(now.hour() < 10)strcat(rtc_read_time, "0");
    strcat(rtc_read_time, itoa(now.hour(), bufor, 10));
    strcat(rtc_read_time, separator);
    if(now.minute() < 10)strcat(rtc_read_time, "0");
    strcat(rtc_read_time, itoa(now.minute(), bufor, 10));
//    strcat(rtc_read_time, separator);
//    if(now.second() < 10)strcat(rtc_read_time, "0");
//    strcat(rtc_read_time, itoa(now.second(), bufor, 10));

    return rtc_read_time;
}

char *RTC_DATE(const char *separator, bool dont_refresh) {
    rtc_read_date[0] = 0;
    if(!dont_refresh)
        RTC_READ();

    char bufor[16];
    strcat(rtc_read_date, itoa(now.year(), bufor, 10));
    strcat(rtc_read_date, separator);
    if(now.month() < 10)strcat(rtc_read_date, "0");
    strcat(rtc_read_date, itoa(now.month(), bufor, 10));
    strcat(rtc_read_date, separator);
    if(now.day() < 10)strcat(rtc_read_date, "0");
    strcat(rtc_read_date, itoa(now.day(), bufor, 10));

    return rtc_read_date;
}
