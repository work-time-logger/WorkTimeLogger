#ifndef CONFIG_MANAGER_INTERFACE_H
#define CONFIG_MANAGER_INTERFACE_H

#include "interfaces/hal/device_capabilities_interface.h"

typedef void (*config_manager_init)(const device_capabilities * device);
typedef void (*config_manager_force)();
typedef char * (*config_manager_get_server)();
typedef char * (*config_manager_get_token)();
typedef char * (*config_manager_get_ota_password)();

struct config_manager_interface
{
    config_manager_init init;
    config_manager_force force;

    config_manager_get_server get_server;
    config_manager_get_token get_token;
    config_manager_get_ota_password get_ota_password;
};

#endif //CONFIG_MANAGER_INTERFACE_H
