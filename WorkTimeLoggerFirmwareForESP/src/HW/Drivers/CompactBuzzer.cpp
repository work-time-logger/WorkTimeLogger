#include "CompactBuzzer.h"

void compact_buzzer_init();
void compact_buzzer_start(float seconds);
void compact_buzzer_stop();

static const struct buzzer_interface compact_buzzer = {
    compact_buzzer_init,
    compact_buzzer_start,
    compact_buzzer_stop
};

const struct buzzer_interface *compact_buzzer_get() {
    return &compact_buzzer;
}

#include <Arduino.h>
#include <Ticker.h>

Ticker compact_buzzer_ticker; // NOLINT(cert-err58-cpp)

void compact_buzzer_init() {
    pinMode(D3, OUTPUT);
    digitalWrite(D3, HIGH);
}

void compact_buzzer_start(float seconds) {
    digitalWrite(D3, LOW);

    compact_buzzer_ticker.once(seconds, compact_buzzer_stop);
}

void compact_buzzer_stop() {
    digitalWrite(D3, HIGH);
}