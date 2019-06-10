#include "FullHAL.h"

#include "Drivers/GenericConfigManager.h"
#include "Drivers/FullBuzzer.h"
#include "Drivers/GenericBuiltInLed.h"
#include "Drivers/VoidLed.h"
#include "Drivers/MFRC522RfidReader.h"
#include "Drivers/GenericOverTheAirUpdater.h"
#include "Drivers/WebApiBackend.h"
#include "Drivers/DS1307Rtc.h"

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