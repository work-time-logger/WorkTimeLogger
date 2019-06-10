#ifndef BACKEND_INTERFACE_H
#define BACKEND_INTERFACE_H

#include "ConfigManagerInterface.h"
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

typedef void (*backend_init)(const config_manager_interface * config);

typedef PingResponse (*backend_ping)();
typedef QueryResponse (*backend_query)(const String& card_id);
typedef StartResponse (*backend_start)(const String& card_id);
typedef EndResponse (*backend_end)(const String& card_id, const String& entry_id);

struct backend_interface
{
    backend_init init;

    backend_ping ping;
    backend_query query;
    backend_start start;
    backend_end end;
};

#endif //BACKEND_INTERFACE_H
