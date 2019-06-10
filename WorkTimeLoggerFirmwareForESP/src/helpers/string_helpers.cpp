#include <stdlib.h>
#include <stdio.h>
#include "string_helpers.h"

void format_minutes(int minutes, char* out)
{
    char buf[50];
    char buf2[50];

    if(minutes <= 60){
        sprintf(out, "%s min.", itoa(minutes, buf, 10));
    }else{
        int min = minutes % 60;
        int hour = minutes / 60;
        if(min < 10)
            sprintf(out, "%s:0%s", itoa(hour, buf, 10), itoa(min, buf2, 10));
        else
            sprintf(out, "%s:%s", itoa(hour, buf, 10), itoa(min, buf2, 10));
    }
}