#include "compact_hal.h"

#include "drivers/generic_config_manager.h"
#include "drivers/compact_buzzer.h"
#include "drivers/compact_success_led.h"
#include "drivers/compact_error_led.h"
#include "drivers/mfrc522_rfid_reader.h"
#include "drivers/generic_ota_updater.h"
#include "drivers/web_api_backend.h"
#include "drivers/void_rtc.h"

static const struct device_capabilities compact_capabilities = {
    "Compact Work Time Logger",
    "Compact WTL Configuration",
    "password",
    false
};

static const struct hal_interface compact_hal = { // NOLINT(cert-err58-cpp)
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