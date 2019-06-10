#ifndef OTA_UPDATER_INTERFACE_H
#define OTA_UPDATER_INTERFACE_H

#include "ConfigManagerInterface.h"

typedef void (*ota_updater_init)(const config_manager_interface * config);
typedef void (*ota_updater_event)();

struct ota_updater_interface
{
    ota_updater_init init;
    ota_updater_event event;
};

#endif //OTA_UPDATER_INTERFACE_H
