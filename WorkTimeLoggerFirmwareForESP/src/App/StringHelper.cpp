// App/StringHelper.cpp

#include <stdlib.h>
#include <stdio.h>
#include "StringHelper.h"

int TransformToMinutes(int minutes_in)
{
    return minutes_in%60;
}

int TransformToHours(int minutes_in)
{
    return minutes_in/60;
}

void format_minutes(int minutes, char* out)
{
    char buf[50];
    char buf2[50];

    if(minutes <= 60){
        sprintf(out, "%s min.", itoa(minutes, buf, 10));
    }else{
        int min = TransformToMinutes(minutes);
        int hour = TransformToHours(minutes);
        if(min < 10)
            sprintf(out, "%s:0%s", itoa(hour, buf, 10), itoa(min, buf2, 10));
        else
            sprintf(out, "%s:%s", itoa(hour, buf, 10), itoa(min, buf2, 10));
    }
}