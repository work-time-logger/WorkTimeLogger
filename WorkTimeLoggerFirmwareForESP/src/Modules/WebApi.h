#ifndef WEBAPI_H
#define WEBAPI_H

#include <WString.h>

struct PingResponse {
    char uuid[50];
    char name[50];
    bool is_active;
};

struct QueryResponse {
    bool valid;
    char employee[50];
    char first_name[50];
    char last_name[50];
    int worked_today;
    int worked_period;
    int open_entry_working;
    char open_entry[50];
    bool has_invalid_entries;
};

struct StartResponse {
    bool valid;
    char start[50];
};

struct EndResponse {
    bool valid;
    char start[50];
    char end[50];
    int worked_minutes;
};

void WEBAPI_TEST();
PingResponse WEBAPI_PING();
QueryResponse WEBAPI_QUERY(const String& card_id);
StartResponse WEBAPI_START(const String& card_id);
EndResponse WEBAPI_END(const String& card_id, const String& entry_id);

#endif //WEBAPI_H
