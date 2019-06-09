// Modules/RTC.h

#ifndef RTC_H
#define RTC_H

#include <RTClib.h>

extern RTC_DS1307 RTC;

void RTC_INIT();
char *RTC_TIME(const char *separator = ":", bool dont_refresh = false);
char* RTC_DATE(const char *separator = "/", bool dont_refresh = false);

#endif //RTC_H
