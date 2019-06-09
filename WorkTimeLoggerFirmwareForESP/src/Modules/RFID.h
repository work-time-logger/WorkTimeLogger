// Modules/RFID.h

#ifndef RFID_H
#define RFID_H

#define RST_PIN D4
#define SS_PIN D8

#include <MFRC522.h>

extern MFRC522 mfrc522;
extern void (*mfrc522_read_callback)(char read_card[]);
extern char mfrc522_read_card[];

void RFID_INIT();
void RFID_EVENT();

#endif //RFID_H
