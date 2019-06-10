#ifndef HAL_INTERFACE_H
#define HAL_INTERFACE_H

#include "BuzzerInterface.h"
#include "LedInterface.h"
#include "RfidReaderInterface.h"
#include "ConfigManagerInterface.h"
#include "DeviceCapabilitiesInterface.h"
#include "OtaUpdaterInterface.h"
#include "BackendInterface.h"
#include "RtcInterface.h"


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
