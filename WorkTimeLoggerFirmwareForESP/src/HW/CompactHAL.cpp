#include "CompactHAL.h"

#include "Drivers/GenericConfigManager.h"
#include "Drivers/CompactBuzzer.h"
#include "Drivers/CompactSuccessLed.h"
#include "Drivers/CompactErrorLed.h"
#include "Drivers/MFRC522RfidReader.h"
#include "Drivers/GenericOverTheAirUpdater.h"
#include "Drivers/WebApiBackend.h"
#include "Drivers/VoidRtc.h"

static const struct device_capabilities compact_capabilities = {
    "Compact Work Time Logger",
    "Compact WTL Configuration",
    "password",
    false
};

static const struct hal_interface compact_hal = {
    .device = &compact_capabilities,
    .config = *generic_config_manager_get(),
    .ota = *generic_ota_updater_get(),
    .backend = *web_api_backend_get(),
    .buzzer = *compact_buzzer_get(),
    .success_led = *compact_success_led_get(),
    .error_led = *compact_error_led_get(),
    .rfid = *mfrc522_rfid_reader_get(),
    .rtc = *void_rtc_get()
};

const struct hal_interface *compact_hal_get() {
    return &compact_hal;
}