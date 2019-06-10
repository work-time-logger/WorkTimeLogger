#include "MFRC522RfidReader.h"

void mfrc522_rfid_reader_init();
void mfrc522_rfid_reader_event();
void mfrc522_rfid_reader_set_callback(void (*callback)(char read_card[]));

static const struct rfid_reader_interface mfrc522_rfid_reader = {
    mfrc522_rfid_reader_init,
    mfrc522_rfid_reader_event,
    mfrc522_rfid_reader_set_callback
};

const struct rfid_reader_interface *mfrc522_rfid_reader_get() {
    return &mfrc522_rfid_reader;
}

#include <MFRC522.h>

MFRC522 mfrc522(SS_PIN, RST_PIN); // NOLINT(cert-err58-cpp)
char mfrc522_read_card[16];
void (*mfrc522_read_callback)(char read_card[]);


void mfrc522_rfid_reader_init() {
    SPI.begin();			// Init SPI bus
    mfrc522.PCD_Init();		// Init MFRC522
}

void mfrc522_rfid_reader_event() {
    if ( ! mfrc522.PICC_IsNewCardPresent()) return;
    if ( ! mfrc522.PICC_ReadCardSerial()) return;

    mfrc522_read_card[0] = 0;
    char bufor[16];
    for ( uint8_t i = 0; i < mfrc522.uid.size; i++) {  //
        if(i)
            strcat(mfrc522_read_card, "-");

        if(mfrc522.uid.uidByte[i] < 0x10)
            strcat(mfrc522_read_card, "0");

        strcat(mfrc522_read_card, itoa(mfrc522.uid.uidByte[i], bufor, 16));
    }

    mfrc522.PICC_HaltA();

    for (uint8_t i = 0; mfrc522_read_card[i] !=0; i++)
        mfrc522_read_card[i] = static_cast<char>(toupper(mfrc522_read_card[i]));

    if(mfrc522_read_callback)
        mfrc522_read_callback(mfrc522_read_card);
}

void mfrc522_rfid_reader_set_callback(void (*callback)(char read_card[])) {
    mfrc522_read_callback = callback;
}