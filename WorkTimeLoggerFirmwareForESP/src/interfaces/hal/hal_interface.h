#ifndef HAL_INTERFACE_H
#define HAL_INTERFACE_H

#include "interfaces/drivers/buzzer_interface.h"
#include "interfaces/drivers/led_interface.h"
#include "interfaces/drivers/rfid_reader_interface.h"
#include "interfaces/drivers/config_manager_interface.h"
#include "device_capabilities_interface.h"
#include "interfaces/drivers/ota_updater_interface.h"
#include "interfaces/drivers/backend_interface.h"
#include "interfaces/drivers/rtc_interface.h"


struct hal_interface
{
    const device_capabilities *device;
    config_manager_interface config;
    ota_updater_interface ota;
    backend_interface backend;
    buzzer_interface buzzer;
    led_interface success_led;
    led_interface error_led;
    rfid_reader_interface rfid;
    rtc_interface rtc;
};

void hal_init(struct hal_interface *t);
void hal_event(struct hal_interface *t);

#endif //HAL_INTERFACE_H
