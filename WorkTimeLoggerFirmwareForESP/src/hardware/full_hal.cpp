#include "full_hal.h"

#include "drivers/generic_config_manager.h"
#include "drivers/full_buzzer.h"
#include "drivers/generic_builtin_led.h"
#include "drivers/void_led.h"
#include "drivers/mfrc522_rfid_reader.h"
#include "drivers/generic_ota_updater.h"
#include "drivers/web_api_backend.h"
#include "drivers/ds1307_rtc.h"

static const struct device_capabilities full_capabilities = {
    "HMI Work Time Logger",
    "HMI WTL Configuration",
    "password",
    true
};

static const struct hal_interface full_hal = {
    .device = &full_capabilities,
    .config = *generic_config_manager_get(),
    .ota = *generic_ota_updater_get(),
    .backend = *web_api_backend_get(),
    .buzzer = *full_buzzer_get(),
    .success_led = *generic_builtin_led_get(),
    .error_led = *void_led_get(),
    .rfid = *mfrc522_rfid_reader_get(),
    .rtc = *ds1307_rtc_get()
};

const struct hal_interface *full_hal_get() {
    return &full_hal;
}