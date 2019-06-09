// Modules/RFID.cpp

#include "RFID.h"
#include "HMI.h"

MFRC522 mfrc522(SS_PIN, RST_PIN);  // Create MFRC522 instance
char mfrc522_read_card[16];

void (*mfrc522_read_callback)(char read_card[]);

void RFID_INIT() {
    SPI.begin();			// Init SPI bus
    mfrc522.PCD_Init();		// Init MFRC522
}

void RFID_EVENT() {
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
