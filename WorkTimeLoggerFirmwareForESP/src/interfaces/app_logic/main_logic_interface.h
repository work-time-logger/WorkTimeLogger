#ifndef MAIN_LOGIC_INTERFACE_H
#define MAIN_LOGIC_INTERFACE_H

#include "interfaces/hal/hal_interface.h"

typedef void (*main_logic_init)(const hal_interface * device);
typedef void (*main_logic_event)(const hal_interface * device);

struct main_logic_interface
{
    main_logic_init init;
    main_logic_event event;
};

#endif //MAIN_LOGIC_INTERFACE_H
